<?php
session_start();
include "config.php";

// Ambil username dan foto profil berdasarkan user_id dari session
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $query = "SELECT username, profile_picture FROM users WHERE id = $user_id";
  $result = $conn->query($query);
  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $profile_picture = $row['profile_picture'];
  } else {
    $username = "User";
    $profile_picture = "profile/default.jpg";
  }

  // Proses perubahan foto profil
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    $target_dir = "profile/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Cek apakah file gambar atau bukan
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
      echo "File yang diupload bukan gambar.";
      $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["profile_picture"]["size"] > 500000) { // 500KB
      echo "Ukuran file terlalu besar.";
      $uploadOk = 0;
    }

    // Cek ekstensi file
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
      echo "Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
      $uploadOk = 0;
    }

    // Cek apakah ada kesalahan dalam proses upload
    if ($uploadOk == 0) {
      echo "File tidak dapat diupload.";
    } else {
      // Upload file
      if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // Update foto profil di database
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $stmt->bind_param("si", $target_file, $user_id);
        if ($stmt->execute()) {
          echo "Foto profil berhasil diubah!";
          // Refresh halaman untuk melihat perubahan
          header("Location: settings.php");
          exit();
        } else {
          echo "Gagal mengubah foto profil.";
        }
      } else {
        echo "Terjadi kesalahan saat mengupload file.";
      }
    }
  }
} else {
  $username = "Guest";
  $profile_picture = "profile/default.jpg";
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
    .profile-picture {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      display: block;
      margin: 0 auto;
    }
    .upload-btn {
      display: block;
      margin: 10px auto;
      padding: 10px 20px;
      background: #469ced;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    .logout-btn {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background: #ff4747;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pengaturan Akun</h2>
    <p class="welcome">Selamat datang, <?php echo htmlspecialchars($username); ?>!</p>
    <div class="profile-section">
      <img src="<?php echo $profile_picture; ?>" alt="Foto Profil" class="profile-picture">
      <form action="settings.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_picture" accept="image/*" required>
        <button type="submit" class="upload-btn">Ubah Foto Profil</button>
      </form>
    </div>
    <ul class="settings-list">
      <li><a href="register.php">Tambahkan User</a></li>
      <li><a href="ubah.php">Ubah Username/Password</a></li>
      <!-- Logout button (Handled outside iframe) -->
      <li><button onclick="logout()" class="logout-btn">Logout</button></li>
    </ul>
  </div>

  <script>
    // Logout function outside the iframe
    function logout() {
      window.location.href = 'logout.php';  // Redirect to logout script to destroy session
    }
  </script>
</body>
</html>
