<?php
include 'config.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM laporan WHERE id=$id");

header("Location: petugas.php");
?>