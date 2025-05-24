<?php
namespace App\Controllers;

use App\Factories\ModelFactory;
use App\Core\View;

class AuthController {
    private $userRepo;

    public function __construct() {

        $this->userRepo = ModelFactory::create('userrepository');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * 
     * @param array $data 
     */
    public function showLoginForm(array $data = []) {
        $view = new View('auth/login');
        $view->render($data);
    }

    /**
     * 
     * @param array $postData 
     */
    public function login(array $postData) {
        $login = trim($postData['login'] ?? '');
        $password = $postData['password'] ?? '';
        $error = '';
        $success = '';
        $showRegister = false; 

        if ($login === '' || $password === '') {
            $error = 'Введите логин и пароль.';
        } else {

            $superUserRepo = ModelFactory::create('superuserrepository') ?? null;
            $user = null;
            if ($superUserRepo) {
                $user = $superUserRepo->getSuperUserByLogin($login);
            }
            if (!$user) {
                $user = $this->userRepo->getUserByUsername($login);
            }

            if ($user && password_verify($password, $user->getPasswordHash())) {

                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_role'] = $user->getRole();
                $_SESSION['user_name'] = $user->getUsername();

                header('Location: /project/public/user/profile');
                exit;
            } else {
                $error = 'Неверный логин или пароль.';
            }
        }

        $this->showLoginForm([
            'error' => $error,
            'success' => $success,
            'showRegister' => $showRegister
        ]);
    }

    /**
     * 
     * @param array 
     */
    public function register(array $postData) {
        $username = trim($postData['reg_username'] ?? '');
        $email = trim($postData['reg_email'] ?? '');
        $password = $postData['reg_password'] ?? '';
        $passwordConfirm = $postData['reg_password_confirm'] ?? '';
        $error = '';
        $success = '';
        $showRegister = true; // показываем форму регистрации

        if ($username === '' || $email === '' || $password === '' || $passwordConfirm === '') {
            $error = 'Пожалуйста, заполните все поля.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Неверный формат email.';
        } elseif ($password !== $passwordConfirm) {
            $error = 'Пароли не совпадают.';
        } else {
            $existingUser = $this->userRepo->getUserByUsername($username);
            if ($existingUser) {
                $error = 'Пользователь с таким логином уже существует.';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $success = $this->userRepo->addUser($username, $email, $passwordHash)
                    ? 'Регистрация прошла успешно! Теперь вы можете войти.'
                    : 'Ошибка при регистрации. Попробуйте позже.';
                if ($success) {
                    $showRegister = false; 
                }
            }
        }

        $this->showLoginForm([
            'error' => $error,
            'success' => $success,
            'showRegister' => $showRegister
        ]);
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /project/public/user/login');
        exit;
    }
}
