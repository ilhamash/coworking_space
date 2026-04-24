<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION["login"])) {
    header("Location: beranda.php");
    exit;
}

if (isset($_POST["login"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];
            header("Location: beranda.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Workspace</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-auth">

    <div class="auth-container">
        <h2>Login</h2>

        <?php if(isset($error)) : ?>
            <p class="error-message">Username atau password salah!</p>
        <?php endif; ?>

        <form action="" method="POST">
            <label>Username</label>
            <input type="text" name="username" required autocomplete="off">
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login" class="btn">Masuk</button>
        </form>
    </div>

</body>
</html>