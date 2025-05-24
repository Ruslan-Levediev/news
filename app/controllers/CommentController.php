<?php
    namespace App\Controllers;
    use App\Factories\ModelFactory;

    class CommentController {
        private $commentRepo;

        public function __construct() {

            $this->commentRepo = ModelFactory::create('commentrepository');
        }

        public function delete(int $comment_id) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
                header('HTTP/1.1 403 Forbidden');
                die('Доступ заборонено');
            }

            $this->commentRepo->deleteCommentById($comment_id);

            $referer = $_SERVER['HTTP_REFERER'] ?? '/project/public/news';
            header("Location: $referer");
            exit;
        }



        public function add(array $postData = []) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                header('Location: /project/public/user/login');
                exit;
            }

            if (empty($_POST['news_id']) || empty($_POST['comment_text'])) {
                header('Location: /project/public/news');
                exit;
            }

            $news_id = (int)$_POST['news_id'];
            $user_id = $_SESSION['user_id'];
            $comment_text = trim($_POST['comment_text']);

            if ($comment_text === '') {
                header("Location: /project/public/news/$news_id");
                exit;
            }

            $this->commentRepo->addComment($news_id, $user_id, htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8'));

            header("Location: /project/public/news/$news_id");
            exit;
        }
    }
