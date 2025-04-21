<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';  // Pastikan koneksi database sudah benar

// Validasi dan ambil parameter GET
if (!isset($_GET['id_user']) || !isset($_GET['aspek'])) {
    echo "Parameter ID dan Aspek tidak lengkap.";
    exit();
}

$id_user = $_GET['id_user'];  // Nomor ID User
$aspek = $_GET['aspek'];  // Aspek Tes

// Ambil data jawaban dan user berdasarkan nomor_id
$query = "SELECT 
             u.nama_lengkap, u.nomor_id, u.pangkat, u.korp, u.satker, u.kategori_tes,
             s.pertanyaan, j.jawaban, j.nilai, j.created_at, s.aspek, s.tipe_soal
         FROM jawaban j
         JOIN users u ON u.id = j.id_user
         JOIN soals s ON s.id = j.id_soal
         WHERE u.nomor_id = ? AND s.aspek = ? 
         ORDER BY j.created_at ASC";


$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $id_user, $aspek);  // Bind parameter nomor_id dan aspek
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Tidak ada data untuk nomor_id dan aspek tersebut.";
    exit();
}

// Ambil data user untuk ditampilkan
$row = $result->fetch_assoc();
$nama_lengkap = $row['nama_lengkap'];
$nomor_id = $row['nomor_id'];
$pangkat = $row['pangkat'];
$korp = $row['korp'];
$satker = $row['satker'];
$kategori_tes = $row['kategori_tes'];

$total_nilai = 0;
$result->data_seek(0); // Reset pointer result


?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Hasil Psikotes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            text-align: center;
        }

        .info { margin-bottom: 30px; font-size: 14px; text-align: left; display: inline-block; }
        .info p { margin: 4px; }
        h2 { margin-bottom: 10px; text-transform: uppercase; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            text-align: left;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th { background-color: #f2f2f2; }
        .print-button {
            margin-top: 30px;
        }
		@media print {
    /* Menyembunyikan URL dan parameter */
    .no-print {
        display: none;
    }
}

    </style>
</head>
<body>

<h2>Hasil Tes Psikologi - <?= strtoupper(str_replace('_', ' ', $aspek)) ?></h2>

<div class="info">
    <p><strong>NRP:</strong> <?= $nomor_id ?></p>
    <p><strong>Nama:</strong> <?= $nama_lengkap ?></p>
    <p><strong>Pangkat/Korp:</strong> <?= $pangkat ?> / <?= $korp ?></p>
    <p><strong>Satker:</strong> <?= $satker ?></p>
    <p><strong>Kategori Tes:</strong> <?= $kategori_tes ?></p>
    <p><strong>Tanggal:</strong> <?= strftime("%e %B %Y", strtotime($row['created_at'])) ?></p>
</div>

<table>
    <tr>
        <th>No</th>
        <th>Pertanyaan</th>
        <th>Jawaban</th>
        <th>Nilai</th>
    </tr>
    <?php
    $no = 1;
    while ($data = $result->fetch_assoc()) {
        $nilai_asli = $data['nilai'];
        $nilai_final = ($data['tipe_soal'] == 'negatif') ? (6 - $nilai_asli) : $nilai_asli;
        $total_nilai += $nilai_final;

        echo "<tr>
                <td>$no</td>
                <td>{$data['pertanyaan']}</td>
                <td>{$data['jawaban']}</td>
                <td>{$nilai_final}</td>
              </tr>";
        $no++;
    }

    // Interpretasi berdasarkan total nilai
    $kategori = "Sedang";
    if ($aspek == 'KESEJAHTERAAN PSIKOLOGIS') {
        if ($total_nilai >= 159) $kategori = "Tinggi";
        elseif ($total_nilai <= 95) $kategori = "Rendah";
    } elseif ($aspek == 'DUKUNGAN SOSIAL') {
        if ($total_nilai >= 72) $kategori = "Tinggi";
        elseif ($total_nilai <= 34) $kategori = "Rendah";
    } elseif ($aspek == 'KEPUASAN KERJA') {
        if ($total_nilai >= 144) $kategori = "Tinggi";
        elseif ($total_nilai <= 68) $kategori = "Rendah";
    }
    ?>
</table>


<h3>Total Skor: <?= $total_nilai ?> / <strong><?= $kategori ?></strong></h3>
<!-- Menyembunyikan URL parameter pada saat mencetak -->

<div class="print-button no-print">
    <button onclick="window.print()">Cetak Halaman Ini</button>
</div>

</body>
</html>
