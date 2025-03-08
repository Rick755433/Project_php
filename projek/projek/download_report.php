<?php
include "config.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID ruangan tidak tersedia.";
    exit();
}

$room_id = intval($_GET['id']); 
$q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : "";


$sql_room = "SELECT name FROM rooms WHERE id = '$room_id'";
$result_room = $conn->query($sql_room);
if ($result_room->num_rows == 0) {
    echo "Ruangan tidak ditemukan.";
    exit();
}
$room = $result_room->fetch_assoc();


header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=Laporan_{$room['name']}.doc");
header("Pragma: no-cache");
header("Expires: 0");


ob_start();
?>

<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        h2 { text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #ddd; }
        .signature { margin-top: 50px; text-align: right; }
        .signature p { margin-bottom: 60px; }
    </style>
</head>
<body>

    <h2>Laporan Data Inventaris</h2>
    <p><strong>Ruangan: <?php echo htmlspecialchars($room['name']); ?></strong></p>
    <p><strong>Tanggal: <?php echo date("d-m-Y"); ?></strong></p>

    <table>
        <tr>
            <th>No.</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Kondisi</th>
            <th>Tanggal Pembelian</th>
            <th>Harga</th>
            <th>Jumlah</th>
        </tr>

        <?php
        $sql_items = "SELECT items.name, categories.name AS category, items.item_condition, 
                      items.purchase_date, items.price, items.quantity 
                      FROM items 
                      LEFT JOIN categories ON items.category_id = categories.id 
                      WHERE items.room_id = '$room_id'";
        if ($q != "") {
            $sql_items .= " AND items.name LIKE '%$q%'";
        }
        $result_items = $conn->query($sql_items);
        $no = 1;

        if ($result_items->num_rows > 0) {
            while ($row = $result_items->fetch_assoc()) {
                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['item_condition']}</td>
                        <td>{$row['purchase_date']}</td>
                        <td>Rp " . number_format($row['price'], 2) . "</td>
                        <td>{$row['quantity']}</td>
                      </tr>";
                $no++;
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada item dalam ruangan ini.</td></tr>";
        }
        ?>

    </table>

    <div class="signature">
        <p>Mengetahui,</p>
        <p><strong>________________________</strong></p>
        <p>(Nama )</p>
    </div>

</body>
</html>

<?php

$content = ob_get_clean();
echo $content;
exit();
?>
