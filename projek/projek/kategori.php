<?php
include "config.php"; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
     $description = $_POST["description"];
    
   
      $sql = "INSERT INTO categories (name, description) VALUES ('$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "Kategori berhasil ditambahkan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
</head>
<body>

<h1>Tambah Kategori</h1>

<form method="post">
    <label for="name">Nama Kategori:</label>
    <input type="text" name="name" placeholder="Nama Kategori" required><br><br>
    
    <label for="description">Deskripsi:</label>
    <textarea name="description" placeholder="Deskripsi Kategori" required></textarea><br><br>
    
    <button type="submit">Tambah Kategori</button>
</form>

</body>
</html>
