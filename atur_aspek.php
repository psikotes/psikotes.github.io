<?php
session_start();
include 'config/db.php';

// Cek apakah sudah login sebagai admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Proses simpan pengaturan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aspek_baru = $_POST['aspek_aktif'];
    
    // Update pengaturan aspek aktif
    $conn->query("UPDATE pengaturan SET aspek_aktif = '$aspek_baru' WHERE id = 1");
    echo "<script>alert('Aspek berhasil diubah!'); window.location='atur_aspek.php';</script>";
    exit();
}

// Ambil aspek aktif saat ini
$data = $conn->query("SELECT aspek_aktif FROM pengaturan WHERE id = 1")->fetch_assoc();
$aspek_aktif = $data['aspek_aktif'];

// Daftar aspek yang tersedia, termasuk opsi "Semua Soal"
$daftar_aspek = ['Semua Soal', 'KESEJAHTERAAN PSIKOLOGIS', 'DUKUNGAN SOSIAL', 'KEPUASAN KERJA'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Aspek Soal Aktif</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 460px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #003366;
            font-weight: 600;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }

        select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            margin-bottom: 25px;
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Atur Aspek Soal Aktif</h2>
    <form method="POST">
        <label for="aspek_aktif">Pilih Aspek:</label>
        <select name="aspek_aktif" id="aspek_aktif" required>
            <option value="">-- Pilih Aspek --</option>
            <?php foreach ($daftar_aspek as $aspek): ?>
                <option value="<?= $aspek; ?>" <?= $aspek == $aspek_aktif ? 'selected' : ''; ?>>
                    <?= ucwords(str_replace('_', ' ', $aspek)); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Simpan Pengaturan</button>
    </form>

    <a href="dashboard.php" class="back-link">? Kembali ke Dashboard</a>
</div>

</body>
</html>
