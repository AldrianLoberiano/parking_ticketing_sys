<?php
session_start();
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('views/login.php');
} else {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            redirect('views/admin/dashboard.php');
            break;
        case 'guard':
            redirect('views/guard/dashboard.php');
            break;
        case 'employee':
            redirect('views/employee/dashboard.php');
            break;
    }
}

