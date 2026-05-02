<?php
$is_logged = !empty($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<?php
$defaults = [
    'class-posts' => '',
    'class-reg' => '',
    'class-log' => '',
    'class-comment' => '',
];
$params = array_merge($defaults, $params ?? []);
?>
<nav style = "background: #1A5276;">
<div class = "nav">
  <a href="/" class="brand">Boardy</a>
  <a href="/messages.php" class="<?php echo htmlspecialchars($params['class-posts']); ?>">Все посты</a>
 
  <?php if ($is_logged): ?>
    <a href="/submit.php" class="<?php echo htmlspecialchars($params['class-comment']); ?>">Добавить пост</a>
</div>
<div class = "nav">
    <span class="hello">Привет, <?= htmlspecialchars($user_name) ?>!</span>
    <a href="/logout.php" class="marked-dark">Выйти</a>
</div>
  <?php else: ?>
    </div>
    <div class = "nav">
    <a href="/login.php" class="<?php echo htmlspecialchars($params['class-log']); ?>">Вход</a>
    <a href="/register.php" class="<?php echo htmlspecialchars($params['class-reg']); ?>">Регистрация</a>
    </div>
  <?php endif; ?>
</nav>
