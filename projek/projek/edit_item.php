<?php
include "config.php";

// Cek apakah ID item ada dalam URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    
    // Ambil data item yang akan diedit
    $sql_item = "SELECT * FROM items WHERE id = $item_id";
    $result_item = $conn->query($sql_item);
    
    if ($result_item->num_rows > 0) {
        $item = $result_item->fetch_assoc();
    } else {
        echo "Item tidak ditemukan.";
        exit;
    }
} else {
    echo "ID item tidak ditemukan.";
    exit;
}

// Menangani penyimpanan item yang sudah diedit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category_id = $_POST["category_id"];
    $room_id = $_POST["room_id"];
    $quantity = $_POST["quantity"];
    $item_condition = $_POST["item_condition"];
    $purchase_date = $_POST["purchase_date"];
    $price = $_POST["price"];
    
    // Menangani upload gambar jika ada gambar baru
    $image_name = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];
    $image_error = $_FILES["image"]["error"];
    $image_size = $_FILES["image"]["size"];

    // Tentukan folder untuk menyimpan gambar
    $target_dir = "uploads/";
    $image_path = $target_dir . basename($image_name);

    if ($image_error === 0) {
        if ($image_size <= 5000000) {
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Mengupdate data item jika ada gambar baru
                $update_sql = "UPDATE items SET name = '$name', category_id = '$category_id', item_condition = '$item_condition', 
                               purchase_date = '$purchase_date', price = '$price', image = '$image_name', room_id = '$room_id', quantity = '$quantity'
                               WHERE id = $item_id";
            }
        }
    } else {
        // Jika tidak ada gambar baru, hanya update data tanpa mengganti gambar
        $update_sql = "UPDATE items SET name = '$name', category_id = '$category_id', item_condition = '$item_condition', 
                       purchase_date = '$purchase_date', price = '$price', room_id = '$room_id', quantity = '$quantity'
                       WHERE id = $item_id";
    }

    // Eksekusi query update
    if ($conn->query($update_sql) === TRUE) {
        echo "Item berhasil diperbarui.";
        header("Location: item.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!-- Form untuk mengedit barang -->
<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" value="<?php echo $item['name']; ?>" required><br>
    
    <select name="category_id" required>
        <option value="">Pilih Kategori</option>
        <?php 
        // Mengambil kategori dari database untuk ditampilkan dalam dropdown
        $sql_categories = "SELECT * FROM categories";
        $result_categories = $conn->query($sql_categories);
        while ($row = $result_categories->fetch_assoc()) { ?>
            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $item['category_id']) echo 'selected'; ?>>
                <?php echo $row['name']; ?>
            </option>
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
            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $item['room_id']) echo 'selected'; ?>>
                <?php echo $row['name']; ?>
            </option>
        <?php } ?>
    </select><br>

    <select name="item_condition" required>
        <option value="baik" <?php if ($item['item_condition'] == 'baik') echo 'selected'; ?>>Baik</option>
        <option value="rusak ringan" <?php if ($item['item_condition'] == 'rusak ringan') echo 'selected'; ?>>Rusak Ringan</option>
        <option value="rusak berat" <?php if ($item['item_condition'] == 'rusak berat') echo 'selected'; ?>>Rusak Berat</option>
    </select><br>

    <input type="date" name="purchase_date" value="<?php echo $item['purchase_date']; ?>" required><br>
    <input type="number" name="price" step="0.01" value="<?php echo $item['price']; ?>" placeholder="Harga" required><br>
    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" placeholder="Jumlah Barang" required><br>

    <!-- Menampilkan gambar lama -->
    <label>Gambar Barang:</label><br>
    <img src="uploads/<?php echo $item['image']; ?>" width="100"><br>
    
    <!-- Input untuk upload gambar baru -->
    <input type="file" name="image"><br> <!-- Input untuk upload file -->
    
    <button type="submit">Update</button>
</form>
