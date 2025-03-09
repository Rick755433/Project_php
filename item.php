<?php
include "config.php";

// Menangani penghapusan item
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM items WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Item berhasil dihapus.";
    } else {
        echo "Error: " . $conn->error;
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
                    echo "Data berhasil ditambahkan";
                } else {
                    echo "ERROR: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Gagal meng-upload gambar.";
            }
        } else {
            echo "Ukuran file terlalu besar. Maksimal 5MB.";
        }
    } else {
        echo "Terjadi kesalahan saat meng-upload gambar.";
    }
}
?>

<!-- Form untuk menambahkan barang -->
<form method="post" enctype="multipart/form-data"> <!-- enctype="multipart/form-data" agar bisa upload file -->
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
    <input type="file" name="image" required><br> <!-- Input untuk upload file -->
    <button type="submit">Submit</button>
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
    // Menampilkan data barang dari database
    $sql_items = "SELECT items.id, items.name, items.item_condition, items.purchase_date, items.price, items.quantity, items.image, categories.name AS category_name, rooms.name AS room_name
                  FROM items
                  JOIN categories ON items.category_id = categories.id
                  JOIN rooms ON items.room_id = rooms.id";
    $result_items = $conn->query($sql_items);
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
        <td><img src="uploads/<?php echo $row['image']; ?>" width="100"></td>
        <td>
            <a href="edit_item.php?id=<?php echo $row['id']; ?>">Edit</a> | 
            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>
