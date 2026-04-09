<?php
$conn = mysqli_connect("localhost", "root", "", "smart_ppsu");

if(!$conn){
    die("Koneksi gagal");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nikname = $_POST['nikname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn,"INSERT INTO users (nikname,email,password)
    VALUES ('$nikname','$email','$password')");

    header("Location: login.html");
    exit();
}
?>