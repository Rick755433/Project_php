<?php
include "config.php";

// Menangani penghapusan item
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM items WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<p class='message success'>Item berhasil dihapus.</p>";
    } else {
        echo "<p class='message error'>Error: " . $conn->error . "</p>";
    }
}

// Menangani penyimpanan item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category_id = $_POST["category_id"];
    $room_id = $_POST["room_id"];
    $quantity = $_POST["quantity"];
    
    // Memeriksa apakah item_condition ada dalam $_POST
    if (isset($_POST["item_condition"])) {
        $item_condition = $_POST["item_condition"];
    } else {
        $item_condition = ''; // Jika tidak ada, beri nilai default
    }

    $purchase_date = $_POST["purchase_date"];
    $price = $_POST["price"];

    // Menangani upload gambar
    $image_name = $_FILES["image"]["name"];  // Nama gambar yang di-upload
    $image_tmp_name = $_FILES["image"]["tmp_name"];  // Nama sementara di server
    $image_error = $_FILES["image"]["error"];  // Error saat upload
    $image_size = $_FILES["image"]["size"];  // Ukuran gambar

    // Tentukan folder untuk menyimpan gambar
    $target_dir = "uploads/";
    $image_path = $target_dir . basename($image_name);  // Path lengkap ke folder upload

    // Cek apakah file berhasil diupload
    if ($image_error === 0) {
        // Pastikan file tidak terlalu besar (misalnya 5MB)
        if ($image_size <= 5000000) {  // Maksimal 5MB
            // Pindahkan file dari direktori sementara ke folder uploads
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $sql = "INSERT INTO items (name, category_id, item_condition, purchase_date, price, image, room_id, quantity)
                        VALUES ('$name', '$category_id', '$item_condition', '$purchase_date', '$price', '$image_name', '$room_id', '$quantity')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "<p class='message success'>Data berhasil ditambahkan.</p>";
                } else {
                    echo "<p class='message error'>ERROR: " . $sql . "<br>" . $conn->error . "</p>";
                }
            } else {
                echo "<p class='message error'>Gagal meng-upload gambar.</p>";
            }
        } else {
            echo "<p class='message error'>Ukuran file terlalu besar. Maksimal 5MB.</p>";
        }
    } else {
        echo "<p class='message error'>Terjadi kesalahan saat meng-upload gambar.</p>";
    }
}

// Menangani pencarian barang
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql_items = "SELECT items.id, items.name, items.item_condition, items.purchase_date, items.price, items.quantity, items.image, 
                     categories.name AS category_name, rooms.name AS room_name
              FROM items
              JOIN categories ON items.category_id = categories.id
              JOIN rooms ON items.room_id = rooms.id";
if ($search != "") {
    $sql_items .= " WHERE items.name LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$result_items = $conn->query($sql_items);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Barang</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f7f6;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 90%;
      max-width: 1200px;
      margin: 20px auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    h1, h2 {
      text-align: center;
      color: #469ced;
    }
    form {
      margin: 20px 0;
      text-align: center;
    }
    form input[type="text"],
    form input[type="date"],
    form input[type="number"],
    form select,
    form input[type="file"] {
      width: calc(50% - 20px);
      padding: 10px;
      margin: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    form button {
      background-color: #469ced;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin: 10px;
    }
    form button:hover {
      background-color: #357ab8;
    }
    .message {
      text-align: center;
      font-weight: bold;
      padding: 10px;
      margin: 10px;
    }
    .success {
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
    table th,
    table td {
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
    img {
      max-width: 100px;
      border-radius: 4px;
    }
    /* Style untuk form pencarian */
    .search-form {
      text-align: center;
      margin: 20px 0;
    }
    .search-form input[type="text"] {
      width: calc(50% - 20px);
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .search-form button {
      background-color: #469ced;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-left: 10px;
    }
    .search-form button:hover {
      background-color: #357ab8;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Tambah Barang</h1>
    <!-- Form untuk menambahkan barang -->
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Nama Barang" required><br>
      <select name="category_id" required>
        <option value="">Pilih Kategori</option>
        <?php 
        // Mengambil kategori dari database untuk ditampilkan dalam dropdown
        $sql_categories = "SELECT * FROM categories";
        $result = $conn->query($sql_categories);
        while ($row = $result->fetch_assoc()) { ?>
          <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php } ?>
      </select><br>
      <!-- Dropdown untuk memilih Ruangan -->
      <select name="room_id" required>
        <option value="">Pilih Ruangan</option>
        <?php
        // Mengambil data ruangan dari database untuk ditampilkan dalam dropdown
        $sql_rooms = "SELECT * FROM rooms";
        $result_rooms = $conn->query($sql_rooms);
        while ($row = $result_rooms->fetch_assoc()) { ?>
          <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php } ?>
      </select><br>
      <select name="item_condition" required>
        <option value="baik">Baik</option>
        <option value="rusak ringan">Rusak Ringan</option>
        <option value="rusak berat">Rusak Berat</option>
      </select><br>
      <input type="date" name="purchase_date" required><br>
      <input type="number" name="price" step="0.01" placeholder="Harga" required><br>
      <input type="number" name="quantity" placeholder="Jumlah Barang" required><br>
      <input type="file" name="image" required><br>
      <button type="submit">Submit</button>
    </form>
    
    <!-- Form pencarian -->
    <form class="search-form" method="get">
      <input type="text" name="search" placeholder="Cari barang..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit">Cari</button>
    </form>
    
    <!-- Menampilkan daftar barang -->
    <h2>Daftar Barang</h2>
    <table border="1">
      <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Kondisi</th>
        <th>Tanggal Pembelian</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Ruangan</th>
        <th>Gambar</th>
        <th>Aksi</th>
      </tr>
      <?php
      while ($row = $result_items->fetch_assoc()) {
      ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['category_name']; ?></td>
        <td><?php echo $row['item_condition']; ?></td>
        <td><?php echo $row['purchase_date']; ?></td>
        <td><?php echo number_format($row['price'], 2); ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['room_name']; ?></td>
        <td><img src="uploads/<?php echo $row['image']; ?>" alt="Gambar Barang"></td>
        <td>
          <a href="edit_item.php?id=<?php echo $row['id']; ?>">Edit</a> | 
          <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
