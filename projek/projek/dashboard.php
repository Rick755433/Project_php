<?php
include "config.php";
session_start();
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $update_last_online = "UPDATE users SET last_online = NOW() WHERE id = '$user_id'";
  $conn->query($update_last_online);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
   
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }
    .container {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 200px;
      background-color: #469ced;
      color: white;
      padding-top: 20px;
      position: fixed;
      height: 100%;
      transition: width 0.3s;
      overflow: hidden;
    }
    
    .toggle-btn {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
      margin: 0 10px 20px 10px;
      outline: none;
    }
    .sidebar h2 {
      margin-left: 5px;
      margin-bottom: 20px;
    }
    .sidebar ul {
      list-style-type: none;
      padding: 0;
    }
    .sidebar ul li {
      margin: 5px 0;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      padding: 10px;
      display: flex;
      align-items: center;
      transition: background-color 0.3s;
    }
    .sidebar ul li a i {
      margin-right: 10px;
      min-width: 20px;
      text-align: center;
    }
    .sidebar ul li a:hover {
      background-color: rgb(249, 243, 243);
      color: black;
    }

    .sidebar.minimized {
      width: 60px;
    }
    .sidebar.minimized .link-text {
      display: none;
    }
    .sidebar.sidebar.minimized h2{
        display: none;
    }
    .sidebar.minimized ul li a:hover {
        background-color: transparent;


    }
    
    
    .content {
      margin-left: 200px;
      padding: 20px;
      flex-grow: 1;
      transition: margin-left 0.3s;
    }
    iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
  </style>
</head>
<body>

<div class="container">
  
  <div class="sidebar">

    <button class="toggle-btn"><i class="fas fa-bars"></i></button>
    <h2>Dashboard</h2>
    <ul>
      <li>
        <a href="isi.php" id="isi-link">
          <i class="fas fa-home"></i>
          <span class="link-text">View</span>
        </a>
      </li>
      <li>
        <a href="ruangan.php" id="ruangan-link">
          <i class="fas fa-building"></i>
          <span class="link-text">Ruangan</span>
        </a>
      </li>
      <li>
        <a href="item.php" id="item-link">
          <i class="fas fa-box"></i>
          <span class="link-text">Item</span>
        </a>
      </li>
      <li>
        <a href="kategori.php" id="kategori-link">
          <i class="fas fa-list"></i>
          <span class="link-text">Kategori</span>
        </a>
      </li>
      <li>
        <a href="user.php" id="user-link">
          <i class="fas fa-user"></i>
          <span class="link-text">User</span>
        </a>
      </li>
    </ul>
  </div>


  <div class="content">
    <iframe id="content-frame" src="isi.php"></iframe>
  </div>
</div>

<script>
  // Toggle sidebar
  var sidebar = document.querySelector('.sidebar');
  var content = document.querySelector('.content');
  document.querySelector('.toggle-btn').addEventListener('click', function() {
      sidebar.classList.toggle('minimized');
      //
      if (sidebar.classList.contains('minimized')) {
          content.style.marginLeft = '60px';
      } else {
          content.style.marginLeft = '200px';
      }
  });

 
  document.getElementById('ruangan-link').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('content-frame').src = 'ruangan.php';
  });
  document.getElementById('item-link').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('content-frame').src = 'item.php';
  });
  document.getElementById('kategori-link').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('content-frame').src = 'kategori.php';
  });
  document.getElementById('isi-link').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('content-frame').src = 'isi.php';
  });
  document.getElementById('user-link').addEventListener('click', function(e) { 
      e.preventDefault();
      document.getElementById('content-frame').src = 'user.php';
  });
</script>


</body>
</html>
