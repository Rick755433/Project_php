<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $location = $_POST["location"];

    $sql = "INSERT INTO rooms(name, location) VALUES ('$name', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='message' style='color: green;'>Data berhasil ditambahkan</p>";
    } else {
        echo "<p class='message' style='color: red;'>ERROR: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$sql_rooms = "SELECT * FROM rooms";
$result_rooms = $conn->query($sql_rooms);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Ruangan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f7f6;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 90%;
      max-width: 800px;
      margin: 30px auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    h1 {
      text-align: center;
      color: #fff;
      background-color: #469ced;
      padding: 15px;
      border-radius: 6px;
      margin-bottom: 20px;
    }
    h2 {
      text-align: center;
      color: #469ced;
      margin-bottom: 15px;
    }
    form {
      margin-bottom: 20px;
      text-align: center;
    }
    form input[type="text"] {
      width: calc(50% - 20px);
      padding: 10px;
      margin: 10px 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    form button {
      background-color: #469ced;
      color: #fff;
      border: none;
      padding: 10px 20px;
      margin-top: 10px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    form button:hover {
      background-color: #357ab8;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
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
    .message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Tambah Ruangan</h1>
    <form method="post">
      <input type="text" name="name" placeholder="Nama Ruangan" required>
      <input type="text" name="location" placeholder="Lokasi" required><br>
      <button type="submit">Submit</button>
    </form>

    <h2>Daftar Ruangan</h2>
    <table>
      <tr>
        <th>Nama Ruangan</th>
        <th>Lokasi</th>
        <th>Aksi</th>
      </tr>
      <?php
      if ($result_rooms->num_rows > 0) {
          while ($row = $result_rooms->fetch_assoc()) {
      ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['location']; ?></td>
        <td>
          <a href="edit_room.php?id=<?php echo $row['id']; ?>">Edit</a> | 
          <a href="delete_room.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?');">Hapus</a>
        </td>
      </tr>
      <?php
          }
      } else {
          echo "<tr><td colspan='3' style='text-align: center;'>Tidak ada data ruangan.</td></tr>";
      }
      ?>
    </table>
  </div>
</body>
</html>
