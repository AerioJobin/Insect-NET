<?php
session_start();

// Define your users and roles
$users = [
    'admin' => [
        'password' => 'iisc_admin_2026', // Change these!
        'role' => 'admin'
    ],
    'researcher' => [
        'password' => 'insect_user_2026',
        'role' => 'user'
    ]
];

// Helper function to check access
function checkAccess($requiredRole = 'user') {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    if ($requiredRole == 'admin' && $_SESSION['role'] != 'admin') {
        die("Access Denied: Admin privileges required.");
    }
}
?>

