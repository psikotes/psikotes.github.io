<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
include 'config/db.php';

// Simpan soal baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pertanyaan = $_POST['pertanyaan'];
    $opsi_a = $_POST['opsi_a'];
    $opsi_b = $_POST['opsi_b'];
    $opsi_c = $_POST['opsi_c'];
    $opsi_d = $_POST['opsi_d'];
    $opsi_e = $_POST['opsi_e'];
    $bobot_a = $_POST['bobot_a'];
    $bobot_b = $_POST['bobot_b'];
    $bobot_c = $_POST['bobot_c'];
    $bobot_d = $_POST['bobot_d'];
    $bobot_e = $_POST['bobot_e'];
    $aspek = $_POST['aspek'];

    $sql = "INSERT INTO soals (pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, bobot_a, bobot_b, bobot_c, bobot_d, bobot_e, aspek)
            VALUES ('$pertanyaan', '$opsi_a', '$opsi_b', '$opsi_c', '$opsi_d', '$opsi_e', $bobot_a, $bobot_b, $bobot_c, $bobot_d, $bobot_e, '$aspek')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Soal berhasil disimpan.</p>";
    } else {
        echo "<p style='color:red;'>Gagal menyimpan soal: " . $conn->error . "</p>";
    }
}

// Filter aspek
$filter = '';
if (isset($_GET['aspek']) && $_GET['aspek'] != '') {
    $filter_aspek = $_GET['aspek'];
    $filter = "WHERE aspek = '$filter_aspek'";
} else {
    $filter_aspek = '';
}
$soals = $conn->query("SELECT * FROM soals $filter ORDER BY id DESC");
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
    }

 form {
    max-width: 800px;
    background: #e3f2fd; /* Soft blue background */
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

input[type="text"], input[type="password"], textarea, select {
    width: 100%;
    padding: 10px;
    margin: 6px 0 16px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
}

button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
;
    }

    textarea, input[type="text"], input[type="number"], select {
        width: 100%;
        padding: 10px;
        margin: 6px 0 16px 0;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .opsi-bobot {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 10px;
    }

    label {
        font-weight: bold;
    }

    button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
    }

    a {
        text-decoration: none;
        color: #007BFF;
    }

    a:hover {
        text-decoration: underline;
    }

    .filter-box {
        margin: 20px 0;
    }
	
</style>

<h2 align="left">Input Soal Psikotes</h2>

<form method="POST">
    <label for="pertanyaan">Pertanyaan:</label>
    <textarea name="pertanyaan" rows="3" required></textarea>

    <label>Opsi Jawaban dan Bobot:</label>
    <div class="opsi-bobot">
        <input type="text" name="opsi_a" placeholder="Opsi A" required>
        <input type="number" name="bobot_a" placeholder="Bobot A" required>
    </div>
    <div class="opsi-bobot">
        <input type="text" name="opsi_b" placeholder="Opsi B" required>
        <input type="number" name="bobot_b" placeholder="Bobot B" required>
    </div>
    <div class="opsi-bobot">
        <input type="text" name="opsi_c" placeholder="Opsi C" required>
        <input type="number" name="bobot_c" placeholder="Bobot C" required>
    </div>
    <div class="opsi-bobot">
        <input type="text" name="opsi_d" placeholder="Opsi D" required>
        <input type="number" name="bobot_d" placeholder="Bobot D" required>
    </div>
    <div class="opsi-bobot">
        <input type="text" name="opsi_e" placeholder="Opsi E" required>
        <input type="number" name="bobot_e" placeholder="Bobot E" required>
    </div>

    <label>Aspek Penilaian:</label>
    <select name="aspek" required>
        <option value="">-- Pilih Aspek --</option>
        <option value="KESEJAHTERAAN PSIKOLOGIS">KESEJAHTERAAN PSIKOLOGIS</option>
        <option value="DUKUNGAN SOSIAL">DUKUNGAN SOSIAL</option>
		 <option value="KEPUASAN KERJA">KEPUASAN KERJA</option>
    </select>

    <br><br>
    <button type="submit">Simpan Soal</button>
</form>

<div class="filter-box">
    <form method="GET">
        <label for="aspek">Filter berdasarkan Aspek:</label>
        <select name="aspek" onchange="this.form.submit()">
            <option value="">-- Semua Aspek --</option>
            <option value="KESEJAHTERAAN PSIKOLOGIS" <?= $filter_aspek == 'KESEJAHTERAAN PSIKOLOGIS' ? 'selected' : '' ?>>KESEJAHTERAAN PSIKOLOGIS Keputusan</option>
            <option value="DUKUNGAN SOSIAL" <?= $filter_aspek == 'DUKUNGAN SOSIAL' ? 'selected' : '' ?>>DUKUNGAN SOSIAL</option>
			            <option value="KEPUASAN KERJA" <?= $filter_aspek == 'KEPUASAN KERJA' ? 'selected' : '' ?>>KEPUASAN KERJA</option>

        </select>
    </form>
</div>

<h3>Daftar Soal</h3>
<table>
    <tr>
        <th>No</th>
        <th>Pertanyaan</th>
        <th>Aspek</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    while ($row = $soals->fetch_assoc()) {
        echo "<tr>
            <td>$no</td>
            <td>{$row['pertanyaan']}</td>
            <td>{$row['aspek']}</td>
            <td>
                <a href='update_soal.php?id={$row['id']}'>Edit</a> | 
                <a href='hapus_soal.php?id={$row['id']}' onclick=\"return confirm('Hapus soal ini?')\">Hapus</a>
            </td>
        </tr>";
        $no++;
    }
    ?>
</table>

<br>
<a href="dashboard.php">? Kembali ke Dashboard</a>
