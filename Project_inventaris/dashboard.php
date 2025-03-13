<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }
    body {
      display: flex;
      height: 100vh;
      background-color: #eef2f3;
    }
    .sidebar {
      width: 250px;
      background-color: #469ced;
      color: white;
      height: 100vh;
      padding: 20px;
      position: fixed;
      transition: width 0.3s ease;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .sidebar.minimized {
      width: 60px;
    }
    .sidebar h2 {
      text-align: center;
      font-size: 1.5rem;
      margin-bottom: 20px;
      transition: opacity 0.3s ease;
    }
    .sidebar.minimized h2, .sidebar.minimized .link-text {
      display: none;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin: 15px 0;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      padding: 12px;
      border-radius: 8px;
      transition: 0.3s;
    }
    .sidebar ul li a i {
      margin-right: 12px;
    }
    .sidebar ul li a:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .toggle-btn {
      background: none;
      border: none;
      color: white;
      font-size: 1.8rem;
      cursor: pointer;
      align-self: flex-start;
    }
    .content {
      margin-left: 250px;
      flex-grow: 1;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }
    .content.minimized {
      margin-left: 60px;
    }
    iframe {
      width: 100%;
      height: calc(100vh - 40px);
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    /* Responsif untuk layar kecil */
    @media screen and (max-width: 768px) {
      .sidebar {
        width: 60px;
      }
      .sidebar h2, .sidebar .link-text {
        display: none;
      }
      .content {
        margin-left: 60px;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <button class="toggle-btn"><i class="fas fa-bars"></i></button>
    <h2>Dashboard</h2>
    <ul>
      <li><a href="isi.php" id="isi-link"><i class="fas fa-home"></i> <span class="link-text">View</span></a></li>
      <li><a href="ruangan.php" id="ruangan-link"><i class="fas fa-building"></i> <span class="link-text">Ruangan</span></a></li>
      <li><a href="item.php" id="item-link"><i class="fas fa-box"></i> <span class="link-text">Item</span></a></li>
      <li><a href="kategori.php" id="kategori-link"><i class="fas fa-list"></i> <span class="link-text">Kategori</span></a></li>
      <li><a href="user.php" id="user-link"><i class="fas fa-user"></i> <span class="link-text">User</span></a></li>
    </ul>
  </div>
  <div class="content">
    <iframe id="content-frame" src="isi.php"></iframe>
  </div>
  <script>
    document.querySelector('.toggle-btn').addEventListener('click', function() {
      let sidebar = document.querySelector('.sidebar');
      let content = document.querySelector('.content');
      sidebar.classList.toggle('minimized');
      content.classList.toggle('minimized');
    });

    document.querySelectorAll('.sidebar ul li a').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('content-frame').src = this.getAttribute('href');
      });
    });
  </script>
</body>
</html>
