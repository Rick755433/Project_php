<?php
include "config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $room_id = $_GET['id'];

   
    $conn->begin_transaction();

    try {
        
        $sql_delete_items = "DELETE FROM items WHERE room_id = '$room_id'";
        if ($conn->query($sql_delete_items) === FALSE) {
            throw new Exception("Gagal menghapus item terkait: " . $conn->error);
        }

    
        $sql_delete_room = "DELETE FROM rooms WHERE id = '$room_id'";
        if ($conn->query($sql_delete_room) === FALSE) {
            throw new Exception("Gagal menghapus ruangan: " . $conn->error);
        }

     
        $conn->commit();
        echo "Ruangan beserta item terkait berhasil dihapus.";
    } catch (Exception $e) {
       
        $conn->rollback();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
    echo "ID ruangan tidak ditemukan.";
}

echo '<br><a href="view_rooms.php">Kembali ke Daftar Ruangan</a>';
?>
