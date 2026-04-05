<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../global/db.php';

require_once __DIR__ . '/../../global/session_start.php';

$client = new Google\Client();
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$client->setRedirectUri(getenv('GOOGLE_REDIRECT_URL'));

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            throw new Exception($token['error_description'] ?? 'Token error');
        }
        $client->setAccessToken($token['access_token']);

        // Get user info
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $google_id = $google_account_info->id;
        $picture = $google_account_info->picture;

        // Check domain restriction: @uca.ac.ma
        if (!str_ends_with($email, '@uca.ac.ma')) {
            $_SESSION['auth_error'] = "Access denied. Only @uca.ac.ma emails are allowed.";
            header('Location: /frontend/pages/auth/login.php');
            exit();
        }

        // Search for user in database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Register new user
            $username = strtolower(str_replace(' ', '.', $name)) . rand(100, 999);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, oauth_provider, oauth_id, is_verified) VALUES (?, ?, 'google', ?, 1)");
            $stmt->execute([$username, $email, $google_id]);
            $user_id = $pdo->lastInsertId();

            // Initial Profile
            $stmt = $pdo->prepare("INSERT INTO profiles (user_id, avatar_url) VALUES (?, ?)");
            $stmt->execute([$user_id, $picture]);

            $user = [
                'id' => $user_id,
                'username' => $username,
                'email' => $email,
                'role_id' => 1
            ];
        } else {
            // Update existing user with Google ID if not present
            if (is_null($user['oauth_id'])) {
                $stmt = $pdo->prepare("UPDATE users SET oauth_provider = 'google', oauth_id = ? WHERE id = ?");
                $stmt->execute([$google_id, $user['id']]);
            }
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];

        header('Location: /frontend/pages/newsfeed/index.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['auth_error'] = "Authentication failed: " . $e->getMessage();
        header('Location: /frontend/pages/auth/login.php');
        exit();
    }
} else {
    header('Location: /frontend/pages/auth/login.php');
    exit();
}
?>