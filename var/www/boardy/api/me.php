<?php
// Паттерн: по куке PHPSESSID вернуть JWT
// React вызывает этот endpoint при загрузке
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax']);  // те же параметры что в login.php
session_start();
 
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}
 
// Паттерн: создать подписанный токен
// base64url: + → -, / → _, без =
$header = rtrim(strtr(base64_encode(json_encode(
    ['alg' => 'HS256', 'typ' => 'JWT']
)), '+/', '-_'), '=');
 
$payload = rtrim(strtr(base64_encode(json_encode([
    'user_id' => $_SESSION['user_id'],
    'name' => $_SESSION['user_name'],
    'exp' => time() + 3600          // 1 час
])), '+/', '-_'), '=');

$secret_key = '231006';
$signature = rtrim(strtr(base64_encode(
    hash_hmac('sha256', "$header.$payload", $secret_key, true)
), '+/', '-_'), '=');
 
$jwt = "$header.$payload.$signature";

header('Content-Type: application/json');
echo json_encode(['token' => $jwt]);
