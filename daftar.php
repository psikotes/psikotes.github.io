<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jenis_id = $_POST['jenis_id'];
    $nomor_id = $_POST['nomor_id'];
    $nama = $_POST['nama_lengkap'];
    $jk = $_POST['jenis_kelamin'];
    $tempat = $_POST['tempat_lahir'];
    $tgl = $_POST['tanggal_lahir'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $kategori = $_POST['kategori_tes'];
    $password = $_POST['password'];
    $pangkat = $_POST['pangkat'];
    $korp = $_POST['korp'];
    $satker = $_POST['satker'];

    $sql = "INSERT INTO users (jenis_id, nomor_id, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, email, alamat, no_hp, kategori_tes, password, pangkat, korp, satker)
            VALUES ('$jenis_id', '$nomor_id', '$nama', '$jk', '$tempat', '$tgl', '$email', '$alamat', '$no_hp', '$kategori', '$password', '$pangkat', '$korp', '$satker')";

    if ($conn->query($sql) === TRUE) {
        header("Location: userlogin.php?success=1");
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran</title>
    <style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: url('3.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.form-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 40px;
    border-radius: 16px;
    max-width: 800px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #333;
}

input, select, textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    background: #f9f9f9;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

input:focus, select:focus, textarea:focus {
    background: #e6f7ff;
    outline: none;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
}

button {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-daftar {
    background-color: #00bfa6;
    color: white;
    margin-bottom: 10px;
}

.btn-daftar:hover {
    background-color: #009e88;
}

.btn-login {
    background-color: #ff5c5c;
    color: white;
    text-align: center;
    display: block;
    text-decoration: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 16px;
    transition: background 0.3s;
}

.btn-login:hover {
    background-color: #e04a4a;
}

.gender-group {
    display: flex;
    gap: 20px;
    margin-bottom: 18px;
}

.gender-group label {
    display: flex;
    align-items: center;
    font-size: 15px;
}

.gender-group input {
    margin-right: 8px;
}
    </style>
</head>
<body>

<div class="form-container">
    <h2>Sign Up</h2>
    <form method="POST" action="daftar.php">
        <select name="jenis_id" required>
            <option value="">Pilih Jenis ID</option>
            <option value="NRP/NIP">NRP</option>
        </select>

        <input type="text" name="nomor_id" placeholder="Nrp/NIP" required>
        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
        <input type="text" name="pangkat" placeholder="Pangkat" required>
        <input type="text" name="korp" placeholder="Korp" required>
        <input type="text" name="satker" placeholder="Satker" required>

        <div class="gender-group">
            <label><input type="radio" name="jenis_kelamin" value="Pria" required> Pria</label>
            <label><input type="radio" name="jenis_kelamin" value="Wanita" required> Wanita</label>
        </div>

        <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" required>
        <input type="date" name="tanggal_lahir" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="alamat" placeholder="Alamat" required></textarea>
        <input type="text" name="no_hp" placeholder="Nomor HP" required>
        <input type="text" name="kategori_tes" placeholder="Kategori Tes" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" class="btn-daftar">Daftar</button>
        <a href="userlogin.php" class="btn-login">Sign In</a>
    </form>
</div>

</body>
</html>
