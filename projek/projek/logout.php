<?php
session_start();
include "config.php";

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    
    $update = $conn->prepare("UPDATE users SET status = 'offline', last_online = NOW() WHERE id = ?");
    $update->bind_param("i", $id);
    $update->execute();
}


session_destroy();
header("Location: login.php");
exit();
?>
