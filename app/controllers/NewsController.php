<?php
namespace App\Controllers;

use App\Factories\ModelFactory;
use App\Core\View;
use App\Context\NewsDisplayContext;
use App\Strategies\SortByDateStrategy;
use App\Context\NewsSorterContext;
use App\Strategies\SortByDateDescStrategy;
use App\Strategies\SortByDateAscStrategy;

class NewsController {
    private $newsRepo;
    private $userRepo;
    private $commentRepo;
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        $this->newsRepo = ModelFactory::create('newsrepository');
        $this->userRepo = ModelFactory::create('userrepository');
        $this->newsRepo = ModelFactory::create('newsrepository');
        $this->commentRepo = ModelFactory::create('commentrepository');
    }

   
    public function list() {
        $newsList = $this->newsRepo->getAllNewsUnsorted();

        $sortOrder = $_GET['sort'] ?? 'desc';

    
        foreach ($newsList as &$news) {
            if (!empty($news['publish_date'])) {
                $utcDate = new \DateTime($news['publish_date'], new \DateTimeZone('UTC'));
                $utcDate->setTimezone(new \DateTimeZone('Europe/Kiev'));
                $news['publish_date_formatted'] = $utcDate->format('Y-m-d H:i:s');
            } else {
                $news['publish_date_formatted'] = '';
            }
        }
        unset($news);

        $context = new NewsSorterContext(
            $sortOrder === 'asc'
                ? new SortByDateAscStrategy()
                : new SortByDateDescStrategy()
        );

        $sortedNews = $context->sort($newsList);

        $view = new View('news/list');
        $view->render([
            'title' => 'Новини - SMGNews',
            'newsList' => $sortedNews,
            'baseUrl' => '/project/public',
            'currentSort' => $sortOrder,
        ]);
    }


    public function delete(int $id) {
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /project/public/user/login');
            exit;
        }

        $userId = $_SESSION['user_id'];


        $newsItem = $this->newsRepo->getNewsById($id);
        if (!$newsItem) {
            http_response_code(404);
            echo "Новость не найдена";
            exit;
        }

  
        if ($newsItem['author_id'] != $userId) {
            http_response_code(403);
            echo "Доступ запрещён";
            exit;
        }

   
        $this->newsRepo->deleteNewsById($id);

   
        header('Location: /project/public/news');
        exit;
    }

    public function search() {
        $searchTerm = trim($_GET['q'] ?? '');

        $newsList = [];
        if ($searchTerm !== '') {
            $newsList = $this->newsRepo->searchNews($searchTerm);
        }

        $view = new View('news/search');
        $view->render([
            'title' => 'Пошук новин - SMGNews',
            'searchTerm' => $searchTerm,
            'newsList' => $newsList,
        ]);
    }

    public function setMain(int $id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            echo 'Доступ заборонено';
            exit;
        }

        $newsItem = $this->newsRepo->getNewsById($id);
        if (!$newsItem) {
            http_response_code(404);
            echo "Новина не знайдена";
            exit;
        }

        $this->newsRepo->setMainNews($id);

        header('Location: /project/public/news/' . $id);
        exit;
    }

    public function unsetMain(int $id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            echo 'Доступ заборонено';
            exit;
        }

        $newsItem = $this->newsRepo->getNewsById($id);
        if (!$newsItem) {
            http_response_code(404);
            echo "Новина не знайдена";
            exit;
        }

        $this->newsRepo->unsetMainNews($id);

        header('Location: /project/public/news/' . $id);
        exit;
    }


    public function detail($id) {
    $newsItem = $this->newsRepo->getNewsById((int)$id);

    if (!$newsItem) {
        $title = 'Новина не знайдена';
    } else {
        $title = $newsItem['title'];
    }

    $baseUrl = '/project/public';
    $comments = $this->commentRepo->getCommentsByNewsId((int)$id);

    $view = new View('news/detail');
    $view->render([
        'title' => $title,
        'newsItem' => $newsItem,
        'baseUrl' => $baseUrl,
        'comments' => $comments,
    ]);
    }



 
    public function addForm() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            die('Доступ запрещен');
        }

        $view = new View('news/add');
        $view->render(['title' => 'Додати новину']);
    }

   
    public function add() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            die('Доступ запрещен');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $authorId = $_SESSION['user_id'] ?? null;

            if ($title === '' || $content === '' || !$authorId) {
                die('Все поля обязательны');
            }

            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;

             
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                    die('Непідтримуваний формат зображення');
                }

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $imagePath = '/uploads/' . $fileName; // Путь для сохранения в базе
                } else {
                    die('Помилка завантаження зображення');
                }
            }

            try {
          
                $newsId = $this->newsRepo->addNews($title, $content, $authorId, $imagePath);

                $this->notifyAllUsers($title);

                header('Location: /project/public/news');
                exit;
            } catch (\PDOException $e) {
                die("Помилка бази данних: " . $e->getMessage());
            }
        }
    }


    private function notifyAllUsers(string $newsTitle): void {
        $message = "Опубліковано нову новину: " . $newsTitle;
        $users = $this->userRepo->getAllUsers();

        foreach ($users as $user) {
            $this->newsRepo->addNotification($user['id'], $message);
        }
    }
}
