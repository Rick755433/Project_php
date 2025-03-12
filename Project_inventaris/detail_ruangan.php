<?php
include "config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    if(isset($_POST['move_selected'])) {
        $target_room = $_POST['target_room'];
        if(isset($_POST['selected_items']) && count($_POST['selected_items']) > 0 && !empty($target_room)) {
            foreach($_POST['selected_items'] as $item_id) {
                $query = "SELECT * FROM items WHERE id = $item_id";
                $result_item = $conn->query($query);
                $item_data = $result_item->fetch_assoc();
                $current_qty = $item_data['quantity'];
                $split_qty = isset($_POST['split_qty'][$item_id]) ? intval($_POST['split_qty'][$item_id]) : $current_qty;
                if($split_qty > $current_qty) {
                    $split_qty = $current_qty;
                }
                if($split_qty < $current_qty) {
                    $new_qty = $current_qty - $split_qty;
                    $update_sql = "UPDATE items SET quantity = $new_qty WHERE id = $item_id";
                    $conn->query($update_sql);
                    $insert_sql = "INSERT INTO items (name, category_id, item_condition, purchase_date, price, image, room_id, quantity)
                                   VALUES ('{$item_data['name']}', '{$item_data['category_id']}', '{$item_data['item_condition']}', '{$item_data['purchase_date']}', '{$item_data['price']}', '{$item_data['image']}', '$target_room', '$split_qty')";
                    $conn->query($insert_sql);
                } else {
                    $update_sql = "UPDATE items SET room_id = '$target_room' WHERE id = $item_id";
                    $conn->query($update_sql);
                }
            }
            echo "Item terpilih berhasil dipindahkan.";
        } else {
            echo "Tidak ada item yang dipilih atau ruangan tujuan belum dipilih.";
        }
    }
    if(isset($_POST['delete_selected'])) {
        if(isset($_POST['selected_items']) && count($_POST['selected_items']) > 0) {
            foreach($_POST['selected_items'] as $item_id) {
                $delete_sql = "DELETE FROM items WHERE id = $item_id";
                $conn->query($delete_sql);
            }
            echo "Item terpilih berhasil dihapus.";
        } else {
            echo "Tidak ada item yang dipilih untuk dihapus.";
        }
    }
} else {
    $room_id = $_GET['id'];
}

$q = "";
if(isset($_GET['q'])) {
    $q = $conn->real_escape_string($_GET['q']);
}

$sql_items = "SELECT items.id, items.name AS item_name, items.item_condition, items.purchase_date, items.price, items.quantity, categories.name AS category_name, items.image
              FROM items
              LEFT JOIN categories ON items.category_id = categories.id
              WHERE items.room_id = '$room_id'";
if($q != "") {
    $sql_items .= " AND items.name LIKE '%$q%'";
}
$result_items = $conn->query($sql_items);

$sql_room = "SELECT name AS room_name, location FROM rooms WHERE id = '$room_id'";
$result_room = $conn->query($sql_room);
$room = $result_room->fetch_assoc();

$sql_target_rooms = "SELECT id, name FROM rooms WHERE id != '$room_id'";
$result_target_rooms = $conn->query($sql_target_rooms);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Ruangan</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Styling tambahan jika diperlukan */
  </style>
</head>
<body>
  <div class="content">
    <h1>Detail Ruangan: <?php echo $room['room_name']; ?></h1>
    <p>Lokasi: <?php echo $room['location']; ?></p>
    
    <form method="GET" action="detail_ruangan.php" class="search-form">
      <input type="hidden" name="id" value="<?php echo $room_id; ?>">
      <input type="text" name="q" placeholder="Cari item..." value="<?php echo htmlspecialchars($q); ?>">
      <button type="submit">Cari</button>
    </form>
    
    <h2>Daftar Item di Ruangan Ini</h2>
    <form method="post">
      <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
      <table border="1">
        <tr>
          <th><input type="checkbox" id="select_all"></th>
          <th>Nama Item</th>
          <th>Kategori</th>
          <th>Kondisi</th>
          <th>Tanggal Pembelian</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Split Qty</th>
          <th>Ruangan</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
        <?php if ($result_items->num_rows > 0): ?>
          <?php while ($row = $result_items->fetch_assoc()): ?>
            <tr>
              <td><input type="checkbox" name="selected_items[]" value="<?php echo $row['id']; ?>"></td>
              <td><?php echo $row['item_name']; ?></td>
              <td><?php echo $row['category_name']; ?></td>
              <td><?php echo $row['item_condition']; ?></td>
              <td><?php echo $row['purchase_date']; ?></td>
              <td><?php echo number_format($row['price'], 2); ?></td>
              <td><?php echo $row['quantity']; ?></td>
              <td>
                <input type="number" name="split_qty[<?php echo $row['id']; ?>]" min="1" max="<?php echo $row['quantity']; ?>" value="<?php echo $row['quantity']; ?>">
              </td>
              <td><?php echo $room['room_name']; ?></td>
              <td>
                <?php if(!empty($row['image'])): ?>
                  <img src="uploads/<?php echo $row['image']; ?>" width="100" height="100">
                <?php else: ?>
                  No Image
                <?php endif; ?>
              </td>
              <td>
                <a href="edit_item.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="11">Tidak ada data item untuk ruangan ini.</td></tr>
        <?php endif; ?>
      </table>
      <br>
      <div>
        <label for="target_room">Pindahkan ke Ruangan:</label>
        <select name="target_room" id="target_room">
          <option value="">Pilih Ruangan</option>
          <?php while($row = $result_target_rooms->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <br>
      <button type="submit" name="move_selected">Pindahkan Item Terpilih</button>
      <button type="submit" name="delete_selected" onclick="return confirm('Apakah Anda yakin ingin menghapus item terpilih?');">Hapus Item Terpilih</button>
    </form>
    <br>
    <!-- Link Download Laporan -->
    <div style="text-align: center; margin-top:20px;">
      <a href="download_report.php?id=<?php echo $room_id; ?>&q=<?php echo urlencode($q); ?>" class="download-btn">Download Laporan</a>
    </div>
    <br>
    <a href="isi.php">Kembali ke View</a>
  </div>
  <script>
    document.getElementById('select_all').addEventListener('change', function() {
      var checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
      for (var checkbox of checkboxes) {
        checkbox.checked = this.checked;
      }
    });
  </script>
</body>
</html>
