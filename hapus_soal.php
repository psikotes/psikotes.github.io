<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include 'config/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Hapus dulu semua jawaban terkait soal ini
    $conn->query("DELETE FROM jawaban WHERE id_soal = $id");

    // Lanjut hapus soal
    $sql = "DELETE FROM soals WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: input_soal.php?msg=deleted");
        exit();
    } else {
        echo "Gagal menghapus soal: " . $conn->error;
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
