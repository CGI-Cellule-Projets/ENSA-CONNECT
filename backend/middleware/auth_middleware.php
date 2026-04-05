<?php
require_once __DIR__ . '/../global/session_start.php';

/**
 * Ensures the user is logged in. Redirects to login page if not.
 * Usage: include 'backend/middleware/auth_middleware.php'; checkAuth();
 */
function checkAuth()
{
    if (!isset($_SESSION['user_id'])) {
        // Not logged in
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        // Adjust the redirect path based on where the user is
        // We assume the frontend is at /frontend/...
        header("Location: $protocol://$host/frontend/pages/auth/login.php");
        exit();
    }
}

/**
 * If the user is already logged in, redirect them away from the login/register pages.
 * Usage: include 'backend/middleware/auth_middleware.php'; redirectIfLoggedIn();
 */
function redirectIfLoggedIn()
{
    if (isset($_SESSION['user_id'])) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        header("Location: $protocol://$host/frontend/pages/newsfeed/index.php");
        exit();
    }
}

/**
 * API version of checkAuth - returns 401 Unauthorized instead of redirecting.
 */
function checkAuthApi()
{
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit();
    }
}
?>