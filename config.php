<?php
session_start();

// ═══ SESSION CONFIGURATION ═══
define('SESSION_TIMEOUT', 1800); // 30 minutes (in seconds)

// Check for session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
    session_destroy();
    $_SESSION = [];
    header("Location: login.php?expired=1");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// ═══ USER DATABASE WITH HASHED PASSWORDS ═══
// Credentials: admin / iisc_admin_2026  |  researcher / insect_user_2026
$users = [
    'admin' => [
        'password' => '$2y$10$nUA.EpLb4PNF7J2ZA.l5t.s52/ErYWhSYlUdZMLarfRG6UhzV9Nca', // VERIFIED: iisc_admin_2026
        'role' => 'admin'
    ],
    'researcher' => [
        'password' => '$2y$10$OwVqklqOHVbN9V0NQLDUCOxPpdZ8XkrUW3sJ57c/8ar9PESP5098i', // VERIFIED: insect_user_2026
        'role' => 'user'
    ]
];

// ═══ CSRF TOKEN GENERATOR ═══
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ═══ CSRF TOKEN VALIDATOR ═══
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// ═══ ACCESS CONTROL FUNCTION ═══
function checkAccess($requiredRole = 'user') {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    
    // Check if session has expired
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_destroy();
        $_SESSION = [];
        header("Location: login.php?expired=1");
        exit();
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    
    // Check role
    if ($requiredRole == 'admin' && $_SESSION['role'] != 'admin') {
        http_response_code(403);
        die("<h1>Access Denied</h1><p>Admin privileges required.</p><a href='index.php'>← Back to Dashboard</a>");
    }
}

// ═══ PASSWORD VERIFICATION ═══
function verifyPassword($plainPassword, $hashedPassword) {
    return password_verify($plainPassword, $hashedPassword);
}

// ═══ PASSWORD HASHING (for generating new hashes) ═══
function hashPassword($plainPassword) {
    return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 10]);
}
?>
