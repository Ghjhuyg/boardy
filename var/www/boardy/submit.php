<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax']);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data['comment'] = trim($_POST['comment'] ?? '');
    if (empty($form_data['comment'])) {
        $error = 'Пост не может быть пустым';
    }
    require_once 'db.php';
    if (empty($error)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('INSERT INTO posts (title, body, author_id) VALUES (?, ?, ?)');
    $stmt->execute(['post', $form_data['comment'], $user_id]); 
    header('Location: /messages.php');
    exit; }
}

include __DIR__ . '/partials/head.php';
$params = ['class-comment' => 'marked-light'];
include __DIR__ . '/partials/nav.php';
?>
 
<main class="reg">
  <div class="menu comment">
     <h2 style = "text-align: left;"> Новый пост </h2>
     <?php if ($error): ?>
         <p style="color: red;"><?= htmlspecialchars($error) ?></p>
     <?php endif; ?>
     <form method="post">
       <label for="comment">Текст</label>
       <textarea name="comment" id="comment" placeholder="Напишите ваше сообщение..."></textarea>
       <div class="comment-down">
       <button type="submit" class="post-comment">Опубликовать</button>
       <a href="/messages.php" class="cancel-comment">Отмена</a>
       </div>
     </form>
  </div>
</main>
 
<?php include __DIR__ . '/partials/foot.php'; ?>