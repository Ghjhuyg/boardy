<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax']);
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        require_once 'db.php';
        
        $stmt = $pdo->prepare('SELECT id, name, password_hash FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: /messages.php');
            exit;
        }
        
        $error = 'Неправильный email или пароль';
    }
}

include __DIR__ . '/partials/head.php';
$params = ['class-posts' => '', 'class-reg' => 'marked-dark'];
include __DIR__ . '/partials/nav.php';
?>
 
<main class="reg">
  <div class="menu login">
     <h2 style = "text-align: left;"> Вход </h2>
     <?php if ($error): ?>
         <p style="color: red;"><?= htmlspecialchars($error) ?></p>
     <?php endif; ?>
     <form method="post">
       <label for="email">Email</label>
       <input type="email" name="email" id="email">
       <label for="password">Пароль</label>
       <input type="password" name="password" id="password">
       <button type="submit">Войти</button>
       <div style="text-align: center; color: #333;">или</div>
       <a class="underbutton-a" href="/oauth-github.php" style="height: 40px; background-color: #24292e; color: white; border-radius: 4px;">Войти через GitHub</a>
     </form>
     <div class="underbutton">Нет аккаунта?<a class="underbutton-a" href="/register.php">Регистрация</a></div>
  </div>
</main>
 
<?php include __DIR__ . '/partials/foot.php'; ?>