<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Hasil Tes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
            color: #333;
        }

        form {
            max-width: 600px;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            font-size: 16px;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="text"] {
            background-color: #f5f5f5;
        }

        select {
            background-color: #f5f5f5;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            background-color: #e8f4f8;
        }

        .form-group select {
            background-color: #e8f4f8;
        }
    </style>
</head>
<body>

<h2>Cetak Hasil Tes Berdasarkan Nomor ID dan Aspek</h2>

<form action="cetak_hasil_id_aspek.php" method="GET" target="_blank">
    <div class="form-group">
        <label for="id_user">Nomor ID User:</label>
        <input type="text" name="id_user" required placeholder="Masukkan Nomor ID User">
    </div>

    <div class="form-group">
        <label for="aspek">Pilih Aspek Tes:</label>
        <select name="aspek" required>
            <option value="">-- Pilih Aspek --</option>
            <option value="KESEJAHTERAAN PSIKOLOGIS">KESEJAHTERAAN PSIKOLOGIS</option>
            <option value="DUKUNGAN SOSIAL">DUKUNGAN SOSIAL</option>
            <option value="KEPUASAN KERJA">KEPUASAN KERJA</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit">Cetak Hasil Tes</button>
    </div>
</form>

</body>
</html>
