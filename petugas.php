<?php
include 'config.php';
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.html");
    exit();
}

// ================= EDIT =================
$edit = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $q = mysqli_query($conn, "SELECT * FROM laporan WHERE id=$id");
    $edit = mysqli_fetch_assoc($q);
}

// ================= DELETE =================
if(isset($_GET['hapus'])){
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM laporan WHERE id=$id");
    header("Location: petugas.php");
    exit();
}

// ================= SIMPAN (CREATE + UPDATE + UPLOAD) =================
if(isset($_POST['simpan'])){
    $id = $_POST['id'] ?? '';
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // upload file
    $fileName = $_FILES['file']['name'];
    $tmpName = $_FILES['file']['tmp_name'];

    if(!is_dir("upload")){
        mkdir("upload");
    }

    if($fileName != ""){
        $path = "upload/" . time() . "_" . $fileName;
        move_uploaded_file($tmpName, $path);
    } else {
        $path = $_POST['old_file'] ?? '';
    }

    if($id == ""){
        // CREATE
        mysqli_query($conn, "INSERT INTO laporan (judul, deskripsi, file)
        VALUES ('$judul','$deskripsi','$path')");
    } else {
        // UPDATE
        mysqli_query($conn, "UPDATE laporan SET 
        judul='$judul',
        deskripsi='$deskripsi',
        file='$path'
        WHERE id=$id");
    }

    header("Location: petugas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Petugas - Smart PPSU</title>
  <link rel="stylesheet" href="petugas.css">
  <style>
    /* ================= FORM LAPORAN ================= */
.form-laporan {
  background: white;
  margin: 20px;
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.form-laporan h3 {
  color: #ff7a00;
  margin-bottom: 15px;
}

.form-laporan input,
.form-laporan textarea {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 12px;
  border: 1px solid #ddd;
  font-size: 14px;
  outline: none;
  transition: 0.3s;
}

.form-laporan input:focus,
.form-laporan textarea:focus {
  border-color: #ff7a00;
  box-shadow: 0 0 8px rgba(255,122,0,0.2);
}

.form-laporan button {
  background: linear-gradient(135deg, #ff7a00, #ff9a3c);
  border: none;
  padding: 12px;
  border-radius: 25px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

.form-laporan button:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(255,122,0,0.3);
}


/* ================= DATA LAPORAN ================= */
.data-laporan {
  margin: 20px;
}

.data-laporan h3 {
  color: #ff7a00;
  margin-bottom: 15px;
}

.laporan-item {
  background: white;
  padding: 18px;
  margin-bottom: 15px;
  border-radius: 16px;
  box-shadow: 0 8px 18px rgba(0,0,0,0.08);
  transition: 0.3s;
}

.laporan-item:hover {
  transform: translateY(-4px);
}

.laporan-item h4 {
  margin-bottom: 5px;
  color: #333;
}

.laporan-item p {
  font-size: 14px;
  color: #666;
  margin-bottom: 10px;
}

/* tombol */
.laporan-actions a {
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 10px;
  font-size: 12px;
  margin-right: 8px;
  color: white;
}

.btn-edit {
  background: #4CAF50;
}

.btn-hapus {
  background: #e53935;
}
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  background: linear-gradient(180deg, #fff7ef, #f8f8f8);
  color: #333;
}

/* ================= NAVBAR ================= */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 22px;
  background: linear-gradient(135deg, #ff7a00, #ff9a3c);
  color: white;
  box-shadow: 0 6px 14px rgba(255,122,0,0.35);
}

.logo {
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 1px;
}

.logo span {
  font-weight: 300;
}

.nav-menu {
  display: flex;
  gap: 18px;
  list-style: none;
}

.nav-menu a {
  color: white;
  text-decoration: none;
  font-size: 14px;
  padding: 6px 12px;
  border-radius: 20px;
  transition: 0.3s;
}

.nav-menu a:hover,
.nav-menu a.active {
  background: rgba(255,255,255,0.25);
  font-weight: 600;
}

/* ================= LOKASI ================= */
.lokasi {
  padding: 22px;
}

.lokasi-card {
  background: linear-gradient(145deg, #fff1e2, #ffe0bd);
  border-radius: 22px;
  height: 170px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: #ff7a00;
  box-shadow: 0 10px 25px rgba(255,122,0,0.25);
  transition: 0.3s;
}

.lokasi-card:hover {
  transform: translateY(-6px) scale(1.02);
}

.lokasi-card .icon {
  font-size: 42px;
  margin-bottom: 10px;
}

.lokasi-card p {
  font-size: 15px;
  font-weight: 600;
}

/* ================= FITUR ================= */
.fitur {
  padding: 20px;
}

.fitur h3 {
  margin-bottom: 16px;
  font-size: 17px;
  font-weight: 700;
  color: #ff7a00;
}

/* GRID */
.grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 18px;
}

/* CARD */
.card {
  background: white;
  border-radius: 22px;
  padding: 22px 16px;
  text-align: center;
  text-decoration: none;
  color: #333;
  box-shadow: 0 12px 26px rgba(0,0,0,0.08);
  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
}

/* Accent strip */
.card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 5px;
  width: 100%;
  background: linear-gradient(90deg, #ff7a00, #ffb066);
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 18px 36px rgba(255,122,0,0.25);
}

.card img {
  width: 66px;
  margin-bottom: 12px;
  transition: 0.3s;
}

.card:hover img {
  transform: scale(1.1);
}

.card p {
  font-size: 14px;
  font-weight: 600;
}

  </style>
</head>
<body>

<hr>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">SMART <span>PPSU</span></div>
  <ul class="nav-menu">
    <li><a href="../Beranda/beranda.html">Beranda</a></li>
    <li><a class="active" href="petugas.php">Petugas</a></li>
    <li><a href="../masyarakat/warga.html">Warga</a></li>
  </ul>
</nav>

<h2>Selamat datang, <?php echo $_SESSION['user']; ?> 👋</h2>

<!-- LOKASI -->
<section class="lokasi">
  <div class="lokasi-card">
    <div class="icon"><a href="../Beranda/beranda.html">📍</a></div>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.7098820048627!2d106.86954287586903!3d-6.301798861676561!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ed00376df05d%3A0x4c1832ffb82d9402!2sKelurahan%20Rambutan!5e0!3m2!1sid!2sid!4v1766480409407!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </div>
</section>

<!-- FITUR -->
<section class="fitur">
  <h3>Fitur Petugas</h3>

  <div class="grid">
    <a href="../presensi html/presensi.html" class="card">
      <img src="https://img.icons8.com/fluency/96/task.png"/>
      <p>Presensi Petugas</p>
    </a>

    <a href="#" class="card">
      <img src="https://img.icons8.com/fluency/96/alarm.png"/>
      <p>Jadwal</p>
    </a>

    <a href="#" class="card">
      <img src="https://img.icons8.com/?size=100&id=2-Cf_3f12bcT&format=png&color=000000"/>
      <p>Kelompok</p>
    </a>

    <a href="../Kendala/Kendala-petugas.html" class="card">
      <img src="https://img.icons8.com/?size=100&id=m8uSNCJYoOnh&format=png&color=000000"/>
      <p>Kendala petugas</p>
    </a>
  </div>
</section>

<!-- FORM -->
<div class="form-laporan">
<h3>Tambah / Edit Laporan</h3>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
    <input type="hidden" name="old_file" value="<?= $edit['file'] ?? '' ?>">

    <input type="text" name="judul" placeholder="Judul laporan"
    value="<?= $edit['judul'] ?? '' ?>" required>

    <textarea name="deskripsi" placeholder="Deskripsi"><?= $edit['deskripsi'] ?? '' ?></textarea>

    <input type="file" name="file">

    <button type="submit" name="simpan">Simpan</button>
</form>
</div>

<!-- DATA -->
<div class="data-laporan">
<h3>Data Laporan</h3>

<?php
$data = mysqli_query($conn, "SELECT * FROM laporan");

while($row = mysqli_fetch_assoc($data)){
?>
<div class="laporan-item">
    <h4><?= $row['judul'] ?></h4>
    <p><?= $row['deskripsi'] ?></p>

    <?php if($row['file']){ ?>
        <a href="<?= $row['file'] ?>" target="_blank">📎 Lihat File</a>
    <?php } ?>

    <div class="laporan-actions">
        <a class="btn-edit" href="?edit=<?= $row['id'] ?>">Edit</a>
        <a class="btn-hapus" href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus data?')">Hapus</a>
    </div>
</div>
<?php } ?>

</div>

</body>
</html>