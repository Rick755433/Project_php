<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$room_id = $_GET['id'];

$sql_items = "SELECT items.id, items.name AS item_name, items.item_condition, items.purchase_date, items.price, items.quantity, categories.name AS category_name, items.image
              FROM items
              LEFT JOIN categories ON items.category_id = categories.id
              WHERE items.room_id = '$room_id'";
$result_items = $conn->query($sql_items);

$sql_room = "SELECT name AS room_name, location FROM rooms WHERE id = '$room_id'";
$result_room = $conn->query($sql_room);
$room = $result_room->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Ruangan</title>
</head>
<body>

<div class="content">
    <h1>Detail Ruangan: <?php echo $room['room_name']; ?></h1>
    <p>Lokasi: <?php echo $room['location']; ?></p>
    <h2>Daftar Item di Ruangan Ini</h2>
    
    <table border="1">
        <tr>
            <th>Nama Item</th>
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
        if ($result_items->num_rows > 0) {
            while ($row = $result_items->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['item_condition']; ?></td>
                    <td><?php echo $row['purchase_date']; ?></td>
                    <td><?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $room['room_name']; ?></td>
                    <td>
                        <?php
                        if (!empty($row['image'])) {
                            echo "<img src='uploads/" . $row['image'] . "' width='100' height='100'>";
                        } else {
                            echo "No Image";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="edit_item.php?id=<?php echo $row['id']; ?>">Edit</a> |
                        <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='9'>Tidak ada data item untuk ruangan ini.</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="view.php">Kembali ke View</a>
</div>

</body>
</html>
