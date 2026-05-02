<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax']);
session_start();
require_once 'db.php';
 
$stmt = $pdo->query(
    'SELECT posts.body, users.name, posts.created_at
     FROM posts
     JOIN users ON posts.author_id = users.id
     ORDER BY posts.created_at DESC'
);
$messages = $stmt->fetchAll();
?>
<?php include __DIR__ . '/partials/head.php'; ?>
<?php 
$params = [
    'class-posts' => 'marked-light'
]; 
include __DIR__ . '/partials/nav.php'; 
?>
 
<main>
  <div style = "padding: 20px 50px;">
    <h2 style = "text-align: left;">Все посты</h2>
    <div class="posts">
    <?php if (empty($messages)): ?>
      <p>Сообщений пока нет.</p>
    <?php else: ?>
      <?php foreach ($messages as $msg): ?>
        <div class="post">
          <div class="post-up">
            <div class="name"><?= htmlspecialchars($msg['name']) ?></div>
            <div class="time"><?= htmlspecialchars($msg['created_at']) ?></div>
          </div>
          <div class="post-down"><?= htmlspecialchars($msg['body']) ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
    </div>
  </div>
</main>
 
<?php include __DIR__ . '/partials/foot.php'; ?>

