<?php
include "config.php";

if(!isset($_GET['id'])){
    header("Location: categories.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM categories WHERE id = $id";
$result = $conn->query($sql);
if($result->num_rows == 0) {
    echo "Kategori tidak ditemukan.";
    exit();
}
$category = $result->fetch_assoc();

$message = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    
    $sql_update = "UPDATE categories SET name = '$name', description = '$description' WHERE id = $id";
    if($conn->query($sql_update) === TRUE) {
        $message = "Kategori berhasil diperbarui!";
    } else {
        $message = "Error: " . $sql_update . "<br>" . $conn->error;
    }
    
    $sql = "SELECT * FROM categories WHERE id = $id";
    $result = $conn->query($sql);
    $category = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Kategori</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #e7f0fb;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #469ced;
    }
    form {
      text-align: center;
    }
    form label {
      font-weight: bold;
      margin-right: 10px;
    }
    form input[type="text"],
    form textarea {
      width: calc(100% - 22px);
      padding: 10px;
      margin: 5px 0 15px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    form button {
      background-color: #469ced;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background-color: #357ab8;
    }
    .message {
      text-align: center;
      font-weight: bold;
      margin-bottom: 15px;
      color: #2e7d32;
    }
    .back-link {
      text-align: center;
      margin-top: 15px;
    }
    .back-link a {
      color: #469ced;
      text-decoration: none;
      font-weight: bold;
    }
    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Edit Kategori</h1>
    <?php if($message != "") { echo "<p class='message'>$message</p>"; } ?>
    <form method="post">
      <label for="name">Nama Kategori:</label><br>
      <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required><br>
      <label for="description">Deskripsi:</label><br>
      <textarea name="description" required><?php echo htmlspecialchars($category['description']); ?></textarea><br>
      <button type="submit">Update Kategori</button>
    </form>
    <div class="back-link">
      <a href="kategoris.php">Kembali ke Daftar Kategori</a>
    </div>
  </div>
</body>
</html>
