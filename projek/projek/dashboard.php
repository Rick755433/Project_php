<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Modern</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
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
      background: #f4f7f6;
      color: white;
    }
    .sidebar {
      width: 250px;
      background: #469ced;
      padding: 20px;
      transition: 0.3s;
      border-top-right-radius: 20px;
      border-bottom-right-radius: 20px;
      overflow: hidden;
    }
    .sidebar.minimized {
      width: 70px;
    }
    .sidebar h2 {
      margin-bottom: 20px;
      transition: 0.3s;
    }
    .sidebar.minimized h2 {
      display: none;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li a {
      display: flex;
      align-items: center;
      padding: 10px;
      color: white;
      text-decoration: none;
      transition: 0.3s;
      border-radius: 8px;
    }
    .sidebar ul li a i {
      margin-right: 10px;
      font-size: 20px;
      transition: 0.3s;
    }
    .sidebar.minimized ul li a i {
      margin-right: 0;
    }
    .sidebar ul li a span {
      transition: 0.3s;
    }
    .sidebar.minimized ul li a span {
      display: none;
    }
    .sidebar ul li a:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .content {
      flex-grow: 1;
      padding: 20px;
      transition: margin-left 0.3s;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .content.shifted {
      margin-left: 70px;
    }
    iframe {
      width: 100%;
      height: 100vh;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      border: none;
    }
    .toggle-btn {
      background: none;
      border: none;
      color: white;
      font-size: 1.5rem;
      cursor: pointer;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <h2>Dashboard</h2>
    <ul>
      <li><a href="isi.php" onclick="loadPage(event, 'isi.php')"><i class="fas fa-home"></i> <span>View</span></a></li>
      <li><a href="ruangan.php" onclick="loadPage(event, 'ruangan.php')"><i class="fas fa-building"></i> <span>Ruangan</span></a></li>
      <li><a href="item.php" onclick="loadPage(event, 'item.php')"><i class="fas fa-box"></i> <span>Item</span></a></li>
      <li><a href="kategori.php" onclick="loadPage(event, 'kategori.php')"><i class="fas fa-list"></i> <span>Kategori</span></a></li>
      <li><a href="user.php" onclick="loadPage(event, 'user.php')"><i class="fas fa-user"></i> <span>User</span></a></li>
    </ul>
  </div>
  
    <iframe id="content-frame" src="isi.php"></iframe>

  <script>
    function loadPage(event, url) {
      event.preventDefault();
      document.getElementById('content-frame').src = url;
    }
    
    function toggleSidebar() {
      var sidebar = document.getElementById('sidebar');
      var content = document.getElementById('content');
      sidebar.classList.toggle('minimized');
      content.classList.toggle('shifted');
    }
  </script>
</body>
</html>