<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_GET['id'];

// Ambil data user
$user = $conn->query("SELECT * FROM users WHERE id = $id_user")->fetch_assoc();

// Ambil jawaban user + soal
$query = $conn->query("
    SELECT s.pertanyaan, j.jawaban, j.nilai
    FROM jawaban j
    JOIN soals s ON j.id_soal = s.id
    WHERE j.id_user = $id_user
");

?>

<h2>Detail Hasil Tes</h2>
<p><strong>Nama:</strong> <?= $user['nama_lengkap'] ?></p>
<p><strong>Nomor ID:</strong> <?= $user['nomor_id'] ?></p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Pertanyaan</th>
        <th>Jawaban</th>
        <th>Nilai</th>
    </tr>
    <?php
    $no = 1;
    while ($row = $query->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['pertanyaan']}</td>
                <td>{$row['jawaban']}</td>
                <td>{$row['nilai']}</td>
              </tr>";
        $no++;
    }
    ?>
</table>

<p><a href="hasil_tes.php">? Kembali ke Hasil Tes</a></p>
