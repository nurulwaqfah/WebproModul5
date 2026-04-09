<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "smart_ppsu");

if(!$conn){
    die("Koneksi gagal");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nikname = $_POST['nikname'] ?? '';
    $password = $_POST['password'] ?? '';

    $data = mysqli_query($conn,"SELECT * FROM users WHERE nikname='$nikname'");
    $user = mysqli_fetch_assoc($data);

    if($user){
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['nikname'];
            header("Location: petugas.php");
            exit();
        }else{
            echo "Password salah";
        }
    }else{
        echo "User tidak ditemukan";
    }

}else{
    echo "Harus dari form login";
}
?>