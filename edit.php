<?php
include 'config.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM laporan WHERE id=$id");
$row = mysqli_fetch_assoc($data);
?>

<h3>Edit Laporan</h3>

<form method="POST">
    <input type="text" name="judul" value="<?php echo $row['judul']; ?>"><br><br>
    <textarea name="deskripsi"><?php echo $row['deskripsi']; ?></textarea><br><br>
    <button type="submit" name="update">Update</button>
</form>

<?php
if(isset($_POST['update'])){
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    mysqli_query($conn, "UPDATE laporan SET 
        judul='$judul',
        deskripsi='$deskripsi'
        WHERE id=$id");

    header("Location: petugas.php");
}
?>