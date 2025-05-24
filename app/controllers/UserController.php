<?php
namespace App\Controllers;

use App\Factories\ModelFactory;
use App\Core\View;

class UserController {
    private $userRepo;
    private $superUserRepo;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

      
        $this->userRepo = ModelFactory::create('userrepository');
        $this->superUserRepo = ModelFactory::create('superuserrepository') ?? null;
    }

    
    public function clearNotifications() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /project/public/user/login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        if (!method_exists($this->userRepo, 'clearNotifications')) {
            throw new \Exception('Метод clearNotifications не реализован в UserRepository');
        }

        $this->userRepo->clearNotifications($userId);

        header('Location: /project/public/user/profile');
        exit;
    }

    
    public function deleteNotification() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /project/public/user/login');
            exit;
        }

        $notificationId = $_GET['id'] ?? null;
        if (!$notificationId) {
            header('Location: /project/public/user/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];

        if (!method_exists($this->userRepo, 'belongsToUser') || !method_exists($this->userRepo, 'deleteNotification')) {
            throw new \Exception('Методы belongsToUser или deleteNotification не реализованы в UserRepository');
        }

      
        if (!$this->userRepo->belongsToUser($notificationId, $userId)) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Доступ заборонено';
            exit;
        }

        $this->userRepo->deleteNotification($notificationId);

        header('Location: /project/public/user/profile');
        exit;
    }

 
    public function markNotificationRead() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /project/public/user/login');
            exit;
        }

        $notificationId = $_GET['id'] ?? null;
        if (!$notificationId) {
            header('Location: /project/public/user/profile');
            exit;
        }

        $userId = $_SESSION['user_id'];

        if (!method_exists($this->userRepo, 'belongsToUser') || !method_exists($this->userRepo, 'markNotificationRead')) {
            throw new \Exception('Методы belongsToUser или markNotificationRead не реализованы в UserRepository');
        }

        if (!$this->userRepo->belongsToUser($notificationId, $userId)) {
            header('HTTP/1.1 403 Forbidden');
            echo 'Доступ заборонено';
            exit;
        }

        
        $this->userRepo->markNotificationRead((int)$notificationId, (int)$userId);

        header('Location: /project/public/user/profile');
        exit;
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /project/public/user/login');
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $displayName = trim($_POST['display_name'] ?? '');

        if ($displayName === '') {
            $_SESSION['error'] = 'Ім\'я не може бути порожнім.';
            header('Location: /project/public/user/profile');
            exit;
        }

        $avatarPath = null;

        if (!empty($_FILES['avatar']['tmp_name'])) {
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileTmp = $_FILES['avatar']['tmp_name'];
            $fileName = basename($_FILES['avatar']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                $_SESSION['error'] = 'Непідтримуваний формат зображення. Дозволені: jpg, jpeg, png, gif.';
                header('Location: /project/public/user/profile');
                exit;
            }

            $newFileName = uniqid('avatar_') . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmp, $destination)) {
                $_SESSION['error'] = 'Помилка завантаження файлу.';
                header('Location: /project/public/user/profile');
                exit;
            }

            $avatarPath = '/uploads/avatars/' . $newFileName;
        }

        if (!method_exists($this->userRepo, 'updateUserProfile')) {
            throw new \Exception('Метод updateUserProfile не реализован в UserRepository');
        }

        $success = $this->userRepo->updateUserProfile($userId, $displayName, $avatarPath);

        if ($success) {
            $user = $this->userRepo->getUserById($userId);
            $_SESSION['user_display_name'] = $user->getDisplayName();
            if ($avatarPath !== null) {
                $_SESSION['user_avatar_path'] = $avatarPath;
            }
            $_SESSION['success'] = 'Профіль успішно оновлено.';
        } else {
            $_SESSION['error'] = 'Помилка оновлення профілю.';
        }

        header('Location: /project/public/user/profile');
        exit;
    }


    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $view = new View('user/not_logged_in');
            $view->render(['title' => 'Профиль']);
            return;
        }

        $userId = (int)$_SESSION['user_id'];

        $user = null;
        if ($this->superUserRepo) {
            $user = $this->superUserRepo->getSuperUserById($userId);
        }
        if (!$user) {
            $user = $this->userRepo->getUserById($userId);
        }

        if (!$user) {
            $view = new View('user/not_found');
            $view->render(['title' => 'Профиль']);
            return;
        }

        if (!method_exists($this->userRepo, 'getUserNotifications')) {
            throw new \Exception('Метод getUserNotifications не реализован в UserRepository');
        }

        $notifications = $this->userRepo->getUserNotifications($userId);

        $view = new View('user/profile');
        $view->render([
            'userName' => $user->getUsername(),
            'userDisplayName' => $user->getDisplayName(),
            'userAvatarPath' => $user->getAvatarPath(),
            'userRole' => $user->getRole(),
            'notifications' => $notifications,
            'baseUrl' => '/project/public'
        ]);
    }
}
