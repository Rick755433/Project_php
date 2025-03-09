<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi panjang password
    if (strlen($password) < 8) {
        echo "Password harus terdiri dari minimal 8 karakter!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Cek apakah username sudah digunakan
        $check_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_user->bind_param("s", $username);
        $check_user->execute();
        $check_user->store_result();

        if ($check_user->num_rows > 0) {
            echo "Username sudah digunakan!";
        } else {
            // Profile default
            $default_profile = "profile/user-profile.png"; 

            // Insert data ke database
            $stmt = $conn->prepare("INSERT INTO users (username, password, profile) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $default_profile);

            if ($stmt->execute()) {
                echo "Registrasi berhasil! <a href='login.php'>Login</a>";
            } else {
                echo "Terjadi kesalahan!";
            }
        }
    }
}
?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
