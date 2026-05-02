<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/db.php';

$client_id     = 'Ov23li6uo1fshO50g9sc';
$client_secret = 'eb064ade35137d288eda77bb935ffcd659f145c5';
$redirect_uri  = 'https://bagaev.ai-info.ru/oauth-callback.php';

if (empty($_GET['state']) || empty($_SESSION['oauth_state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
    die('Invalid state — possible CSRF attack');
}

if (empty($_GET['code'])) {
    die('No code provided');
}

$ch = curl_init('https://github.com/login/oauth/access_token');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'code'          => $_GET['code'],
        'redirect_uri'  => $redirect_uri,
    ]),
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
    CURLOPT_RETURNTRANSFER => true,
]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($response['access_token'])) {
    die('Failed to get access token: ' . json_encode($response));
}
$access_token = $response['access_token'];

$ch = curl_init('https://api.github.com/user');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token",
        'User-Agent: Boardy'
    ],
    CURLOPT_RETURNTRANSFER => true,
]);
$profile = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($profile['id'])) {
    die('Failed to get user profile');
}

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE github_id = ?');
$stmt->execute([$profile['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $pdo->prepare('INSERT INTO users (name, github_id) VALUES (?, ?)');
    $stmt->execute([$profile['login'], $profile['id']]);
    $user = [
        'id'   => $pdo->lastInsertId(),
        'name' => $profile['login']
    ];
}

$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['github_login_just_done'] = true;
header('Location: /messages.php');
exit;