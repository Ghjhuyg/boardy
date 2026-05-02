<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

$github_client_id = 'Ov23li6uo1fshO50g9sc';

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$params = http_build_query([
    'client_id' => $github_client_id,
    'redirect_uri' => 'https://bagaev.ai-info.ru/oauth-callback.php',
    'scope' => 'read:user',
    'state' => $state,
]);

header('Location: https://github.com/login/oauth/authorize?' . $params);
exit;