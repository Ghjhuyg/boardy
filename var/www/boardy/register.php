<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax']);
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_data['name'] = trim($_POST['name'] ?? '');
    $form_data['email'] = trim($_POST['email'] ?? '');
    $form_data['password'] = $_POST['password'] ?? '';
    if (empty($form_data['name'])) {
        $errors['name'] = 'Имя обязательно';
    }
    if (empty($form_data['email'])) {
        $errors['email'] = 'Email обязателен';
    }
    require_once 'db.php';
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$form_data['email']]);
    if ($stmt->fetch()) {
        $errors['email'] = 'Такой Email уже зарегистрирован';
    }
    if (empty($form_data['password'])) {
        $errors['password'] = 'Пароль обязателен';
    } elseif (strlen($form_data['password']) < 6) {
        $errors['password'] = 'Пароль должен быть минимум 6 символов';
    }
    if (empty($errors)) {
    $hash = password_hash($form_data['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
    $stmt->execute([$form_data['name'], $form_data['email'], $hash]);
    $new_id = $pdo->lastInsertId();  
    $_SESSION['user_id'] = $new_id;
    $_SESSION['user_name'] = $form_data['name'];
    header('Location: /messages.php');
    exit; }
}?>
<?php include __DIR__ . '/partials/head.php';
$params = [
    'class-posts' => '',
    'class-reg' => 'marked-dark'
]; 
include __DIR__ . '/partials/nav.php'; 
?>
 
<main class="reg">
  <div class="menu">
     <h2 style = "text-align: left;"> Регистрация </h2>
     <?php if ($errors): ?>
         <div class="error-messages">
             <?php foreach ($errors as $error): ?>
                 <p class="error" style="color: red;"><?= htmlspecialchars($error) ?></p>
             <?php endforeach; ?>
         </div>
     <?php endif; ?>
     <form method="post">
       <label for="name">Имя</label>
       <input type="text" name="name" id="name" value="<?= htmlspecialchars($form_data['name']) ?>">
       <label for="email">Email</label>
       <input type="email" name="email" id="email" value="<?= htmlspecialchars($form_data['email']) ?>">
       <label for="password">Пароль</label>
       <input type="password" name="password" id="password">
       <button type="submit">Зарегистрироваться</button>
     </form>
     <div class="underbutton">Уже есть аккаунт?<a class="underbutton-a" href="/login.php">Войти</a></div>
  </div>
</main>
 
<?php include __DIR__ . '/partials/foot.php'; ?>