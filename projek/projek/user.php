<?php
session_start();
include "config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = "SELECT username, profile FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($username, $profile);
    $stmt->fetch();

    
    if (empty($profile)) {
        $profile = "profile/user-profile.png";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $file_name = basename($_FILES['profile_pic']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_exts = ["jpg", "jpeg", "png", "gif"];

        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = "profile_" . $user_id . "." . $file_ext;
            $target_file = "profile/" . $new_file_name;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                // Update profile di database
                $update = $conn->prepare("UPDATE users SET profile = ? WHERE id = ?");
                $update->bind_param("si", $target_file, $user_id);
                if ($update->execute()) {
                    echo "Foto profil berhasil diperbarui!";
                    $profile = $target_file; 
                } else {
                 
                }
            } else {
             
            }
        } else {
            
        }
    } else {
        
    }
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
      text-align: center;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #469ced;
      margin-bottom: 15px;
    }
    .settings-form {
      margin-top: 15px;
    }
    .settings-form input {
      margin-bottom: 10px;
    }
    .settings-list {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }
    .settings-list li {
      border-bottom: 1px solid #ddd;
    }
    .settings-list li:last-child {
      border-bottom: none;
    }
    .settings-list a {
      display: block;
      padding: 15px;
      text-decoration: none;
      color: #333;
      transition: background 0.3s ease;
    }
    .settings-list a:hover {
      background: #f0f8ff;
    }
    button{
      padding: 7px;
      font-size: 12px;
      background-color: #469ced;
      color: white;
      border: none;
      border-radius: 7px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Pengaturan Akun</h2>
    <img src="<?php echo htmlspecialchars($profile); ?>" alt="Profile Picture" class="profile-img">
    <p>Selamat datang, <?php echo htmlspecialchars($username); ?>!</p>

    <form method="POST" enctype="multipart/form-data" class="settings-form">
      <label for="profile_pic">Ubah Foto Profil:</label>
      <input type="file" name="profile_pic" accept="image/*">
      <button type="submit">Upload</button>
    </form>

    <ul class="settings-list">
      <li><a href="register.php">Tambahkan User</a></li>
      <li><a href="ubah.php">Ubah Username/Password</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</body>
</html>
