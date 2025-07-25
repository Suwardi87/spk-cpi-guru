<?php
session_start();
include 'config/db.php';

// Proses login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='$username' AND password='$password'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SPK CPI</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { 
      background-color: #e3f2fd; 
      min-height: 100vh;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    .login-box {
      max-width: 400px;
      margin: 80px auto;
      padding: 32px 28px 24px 28px;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 6px 32px rgba(0,105,92,0.10), 0 1.5px 6px rgba(0,105,92,0.08);
      border-top: 6px solid #00695c;
    }
    .login-logo {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 18px;
    }
    .login-logo img {
      width: 56px;
      height: 56px;
      object-fit: contain;
      margin-right: 10px;
    }
    .login-title {
      color: #00695c;
      font-weight: bold;
      font-size: 1.35rem;
      text-align: center;
      margin-bottom: 8px;
      letter-spacing: 1px;
    }
    .login-subtitle {
      color: #333;
      font-size: 1rem;
      text-align: center;
      margin-bottom: 18px;
    }
    .form-label {
      color: #00695c;
      font-weight: 500;
    }
    .btn-primary {
      background-color: #00695c;
      border-color: #00695c;
      border-radius: 8px;
      font-weight: 500;
      letter-spacing: 1px;
    }
    .btn-primary:hover {
      background-color: #004d40;
      border-color: #004d40;
    }
    .alert-danger {
      border-radius: 8px;
      font-size: 0.98rem;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="login-logo">
    <img src="assets/logo.jpg" alt="Logo Sekolah">
    <span class="login-title">Sistem Penilaian Guru</span>
  </div>
  <div class="login-subtitle">Login ke Aplikasi SPK CPI</div>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Username</label>
      <input type="text" name="username" class="form-control" required autofocus autocomplete="username">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required autocomplete="current-password">
    </div>
    <button class="btn btn-primary w-100" name="login">Login</button>
  </form>
</div>

</body>
</html>
