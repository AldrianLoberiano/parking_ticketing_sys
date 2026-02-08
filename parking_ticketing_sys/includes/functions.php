<?php
// Utility functions

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function getCurrentUser()
{
    if (!isLoggedIn()) return null;
    return $_SESSION['user'];
}

function hasRole($role)
{
    $user = getCurrentUser();
    return $user && $user['role'] === $role;
}

function requireRole($role)
{
    if (!hasRole($role)) {
        redirect('/parking_ticketing_sys/parking_ticketing_sys/views/login.php?error=access_denied');
    }
}

function flashMessage($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

