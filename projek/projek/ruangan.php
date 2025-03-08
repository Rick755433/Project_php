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
        echo "Data berhasil ditambahkan";
    } else {
        echo "ERROR: " . $sql . "<br>" . $conn->error;
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
</head>
<style>
    body{
        padding: 0;
        margin: 0;
    }
    h1{
        width:100vh ;
        padding: 5px;
    }


</style>
<body>
    <div class="da">
    <h1>Tambah Ruangan</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Nama Ruangan" required><br>
        <input type="text" name="location" placeholder="Lokasi" required><br>
        <button type="submit">Submit</button>
    </form>

    </div>



    <h2>Daftar Ruangan</h2>
    <table border="1">
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
            echo "<tr><td colspan='3'>Tidak ada data ruangan.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
