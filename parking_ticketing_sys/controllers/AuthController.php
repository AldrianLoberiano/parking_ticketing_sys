<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';

class AuthController
{
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];

            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user;

                switch ($user['role']) {
                    case 'admin':
                        redirect('/parking_ticketing_sys/parking_ticketing_sys/views/admin/dashboard.php');
                        break;
                    case 'guard':
                        redirect('/parking_ticketing_sys/parking_ticketing_sys/views/guard/dashboard.php');
                        break;
                    case 'employee':
                        redirect('/parking_ticketing_sys/parking_ticketing_sys/views/employee/dashboard.php');
                        break;
                }
            } else {
                flashMessage('error', 'Invalid email or password');
                redirect('/parking_ticketing_sys/parking_ticketing_sys/views/login.php');
            }
        }
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        redirect('/parking_ticketing_sys/parking_ticketing_sys/views/login.php');
    }

    public static function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $role = sanitize($_POST['role']);

            if (User::create($name, $email, $password, $role)) {
                flashMessage('success', 'User registered successfully');
                redirect('../views/login.php');
            } else {
                flashMessage('error', 'Registration failed');
                redirect('../views/register.php');
            }
        }
    }
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'login') {
        AuthController::login();
    } elseif ($action == 'logout') {
        AuthController::logout();
    } elseif ($action == 'register') {
        AuthController::register();
    }
}

