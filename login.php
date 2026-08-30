<?php
declare(strict_types=1);

session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = require __DIR__ . '/config.php';
    $password = (string)($_POST['password'] ?? '');
    if ($password !== '' && password_verify($password, $config['admin_password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['authed'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'パスワードが違います。';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン - 訪問看護 空き枠管理</title>
<style>
  body{ font-family:"Hiragino Kaku Gothic ProN","Yu Gothic Medium","Meiryo",sans-serif; background:#F3F6F4; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
  form{ background:#fff; border:1px solid #DCE5E1; border-radius:10px; padding:32px 28px; width:100%; max-width:320px; box-sizing:border-box; }
  h1{ font-size:16px; color:#1D4B44; margin:0 0 18px; }
  input[type=password]{ width:100%; padding:10px; border:1px solid #DCE5E1; border-radius:7px; font-size:14px; box-sizing:border-box; margin-bottom:14px; }
  button{ width:100%; padding:10px; border:none; border-radius:7px; background:#2C6E64; color:#fff; font-size:14px; font-weight:700; cursor:pointer; }
  button:hover{ background:#1D4B44; }
  .err{ color:#A24B3E; font-size:12.5px; margin-bottom:12px; }
</style>
</head>
<body>
<form method="post">
  <h1>訪問看護<br>空き枠管理アプリ</h1>
  <?php if ($error !== ''): ?><div class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
  <input type="password" name="password" placeholder="パスワード" autofocus required>
  <button type="submit">ログイン</button>
</form>
</body>
</html>
