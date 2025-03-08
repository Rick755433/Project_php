<?php
session_start();
include "config.php";

// Ambil username berdasarkan user_id dari session
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $query = "SELECT username FROM users WHERE id = $user_id";
  $result = $conn->query($query);
  if($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
  } else {
    $username = "User";
  }
} else {
  $username = "Guest";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Akun</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f7f6;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
    }
    .container h2 {
      text-align: center;
      color: #469ced;
      margin-bottom: 20px;
    }
    .welcome {
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.2em;
    }
    .settings-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .settings-list li {
      border-bottom: 1px solid #ddd;
    }
    .settings-list li:last-child {
      border-bottom: none;
    }
    .settings-list a {
      display: block;
      padding: 15px 20px;
      text-decoration: none;
      color: #333;
      transition: background 0.3s ease;
    }
    .settings-list a:hover {
      background: #f0f8ff;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pengaturan Akun</h2>
    <p class="welcome">Selamat datang, <?php echo htmlspecialchars($username); ?>!</p>
    <ul class="settings-list">
      <li><a href="register.php">Tambahkan User</a></li>
      <li><a href="ubah.php">Ubah Username/Password</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</body>
</html>
