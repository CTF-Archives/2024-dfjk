<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $valid_username = 'admin';
    $valid_password = 'qwe123!@#';

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: admin.html');
        exit;
    } else {
        echo '<script>alert("Invalid username or password.");window.location.href="index.html";</script>';
    }
} else {
    header('Location: index.html');
    exit;
}
?>