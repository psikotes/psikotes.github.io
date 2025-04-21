<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'config/db.php';

$queryUser = $conn->query("
    SELECT DISTINCT u.id, u.nama_lengkap, u.nomor_id, u.kategori_tes, u.jenis_id, u.pangkat, u.korp, u.satker
    FROM users u
    JOIN jawaban j ON u.id = j.id_user
");
// Ambil tanggal saat ini dalam format Indonesia
$tanggalTes = date("d-m-Y");?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Tes Psikologi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        .tinggi { color: #4CAF50; font-weight: bold; }
        .sedang { color: #FFC107; font-weight: bold; }
        .rendah { color: #F44336; font-weight: bold; }

        @media print {
            button[onclick="window.print();"] {
                display: none;
            }
        }
    </style>
</head>
<body>

<h2>Hasil Tes Psikologi</h2>

<!-- Menampilkan Tanggal Tes -->
<p><strong>Tanggal Tes:</strong> <?php echo $tanggalTes; ?></p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NRP</th>
            <th>Nama</th>
            <th>Pangkat / Korp</th>
            <th>Satker</th>
            <th>Kategori Tes</th>
            <th>Kesejahteraan Psikologis</th>
            <th>Dukungan Sosial</th>
            <th>Kepuasan Kerja</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $no = 1;
    while ($user = $queryUser->fetch_assoc()):
        $skor = [
            'kesejahteraan_psikologis' => ['nilai' => '-', 'kategori' => ''],
            'dukungan_sosial' => ['nilai' => '-', 'kategori' => ''],
            'kepuasan_kerja' => ['nilai' => '-', 'kategori' => '']
        ];

        $aspekQuery = $conn->query("
            SELECT s.aspek, SUM(
                CASE WHEN s.tipe_soal = 'negatif' THEN (6 - j.nilai) ELSE j.nilai END
            ) as total_nilai
            FROM jawaban j
            JOIN soals s ON j.id_soal = s.id
            WHERE j.id_user = {$user['id']}
            GROUP BY s.aspek
        ");

        while ($aspek = $aspekQuery->fetch_assoc()) {
            $aspek_key = strtolower(str_replace(' ', '_', $aspek['aspek']));
            $nilai = $aspek['total_nilai'];
            $kategori = "Sedang";

            if ($aspek_key == 'kesejahteraan_psikologis') {
                if ($nilai >= 159) $kategori = "Tinggi";
                elseif ($nilai <= 95) $kategori = "Rendah";
            } elseif ($aspek_key == 'dukungan_sosial') {
                if ($nilai >= 72) $kategori = "Tinggi";
                elseif ($nilai <= 34) $kategori = "Rendah";
            } elseif ($aspek_key == 'kepuasan_kerja') {
                if ($nilai >= 144) $kategori = "Tinggi";
                elseif ($nilai <= 68) $kategori = "Rendah";
            }

            $skor[$aspek_key] = [
                'nilai' => $nilai,
                'kategori' => $kategori
            ];
        }
    ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $user['nomor_id'] ?></td>
            <td><?= $user['nama_lengkap'] ?></td>
            <td><?= $user['pangkat'] ?>/<?= $user['korp'] ?></td>
            <td><?= $user['satker'] ?></td>
            <td><?= $user['kategori_tes'] ?></td>
            <?php foreach (['kesejahteraan_psikologis', 'dukungan_sosial', 'kepuasan_kerja'] as $aspek): ?>
                <?php
                    $k = strtolower($skor[$aspek]['kategori']);
                    $nilai = $skor[$aspek]['nilai'];
                    $kategori = $skor[$aspek]['kategori'];
                ?>
                <td class="<?= $k ?>">
                    <?= ($nilai !== '-') ? "{$nilai} <br> ({$kategori})" : "-" ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Tombol Cetak -->
<button onclick="window.print();" style="background-color: #0072ff;">Cetak Tes</button>

</body>
</html>
