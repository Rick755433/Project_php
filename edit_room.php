<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

    
    $sql_room = "SELECT * FROM rooms WHERE id = '$room_id'";
    $result_room = $conn->query($sql_room);
    $room = $result_room->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST["name"];
        $location = $_POST["location"];

        // Update data ruangan
        $sql_update = "UPDATE rooms SET name = '$name', location = '$location' WHERE id = '$room_id'";

        if ($conn->query($sql_update) === TRUE) {
            echo "Data berhasil diupdate";
        } else {
            echo "ERROR: " . $sql_update . "<br>" . $conn->error;
        }
    }
} else {
    echo "ID ruangan tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ruangan</title>
</head>
<body>

    <h1>Edit Ruangan</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Nama Ruangan" value="<?php echo $room['name']; ?>" required><br>
        <input type="text" name="location" placeholder="Lokasi" value="<?php echo $room['location']; ?>" required><br>
        <button type="submit">Update</button>
    </form>

</body>
</html>
