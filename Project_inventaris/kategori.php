<?php
include "config.php";

$message = "";

// Menangani penghapusan kategori jika parameter delete_id tersedia
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM categories WHERE id = $delete_id";
    if ($conn->query($sql_delete) === TRUE) {
        $message = "Kategori berhasil dihapus!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Menangani penambahan kategori
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    
    $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";
    if ($conn->query($sql) === TRUE) {
        $message = "Kategori berhasil ditambahkan!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Kategori</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f7f6;
      margin: 0;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h1, h2 {
      text-align: center;
      color: #469ced;
    }
    form {
      margin-bottom: 30px;
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
    .error {
      color: #d32f2f;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }
    table th {
      background-color: #469ced;
      color: #fff;
    }
    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    table a {
      color: #469ced;
      text-decoration: none;
      font-weight: bold;
    }
    table a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Kelola Kategori</h1>
    <?php if ($message != "") { echo "<p class='message'>$message</p>"; } ?>
    <form method="post">
      <label for="name">Nama Kategori:</label><br>
      <input type="text" name="name" placeholder="Nama Kategori" required><br>
      <label for="description">Deskripsi:</label><br>
      <textarea name="description" placeholder="Deskripsi Kategori" required></textarea><br>
      <button type="submit">Tambah Kategori</button>
    </form>
    
    <h2>Daftar Kategori</h2>
    <table>
      <tr>
        <th>Nama Kategori</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
      <?php
      if ($result_categories->num_rows > 0) {
          while($row = $result_categories->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['description']) . "</td>";
              echo "<td>
                      <a href='edit_category.php?id=" . $row['id'] . "'>Edit</a> | 
                      <a href='?delete_id=" . $row['id'] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus kategori ini?');\">Hapus</a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='3' style='text-align:center;'>Tidak ada kategori</td></tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
