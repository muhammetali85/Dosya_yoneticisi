<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['password'] === '1234') {
    $_SESSION['logged_in'] = true;
    header('Location: index.php');
    exit;
  } else {
    $error = 'Hatalı şifre!';
  }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş Yap</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2 class="mb-4">🔐 Dosya Gezginine Giriş</h2>
  <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
  <form method="post">
    <input type="password" name="password" class="form-control mb-3" placeholder="Şifre">
    <button type="submit" class="btn btn-primary">Giriş Yap</button>
  </form>
</body>
</html>
