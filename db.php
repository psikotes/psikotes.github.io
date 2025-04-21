<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // default XAMPP tanpa password
$dbname = 'psikotes';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
