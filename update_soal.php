<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID soal tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']);
$query = $conn->query("SELECT * FROM soals WHERE id = $id");

if ($query->num_rows == 0) {
    echo "Soal tidak ditemukan.";
    exit();
}

$soal = $query->fetch_assoc();

// Proses update
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

    $sql = "UPDATE soals SET 
        pertanyaan='$pertanyaan', 
        opsi_a='$opsi_a', opsi_b='$opsi_b', opsi_c='$opsi_c', 
        opsi_d='$opsi_d', opsi_e='$opsi_e',
        bobot_a=$bobot_a, bobot_b=$bobot_b, bobot_c=$bobot_c, 
        bobot_d=$bobot_d, bobot_e=$bobot_e,
        aspek='$aspek' 
        WHERE id=$id";

    if ($conn->query($sql)) {
        echo "<p style='color:green;'>Soal berhasil diperbarui.</p>";
        $query = $conn->query("SELECT * FROM soals WHERE id = $id");
        $soal = $query->fetch_assoc();
    } else {
        echo "<p style='color:red;'>Gagal memperbarui soal: " . $conn->error . "</p>";
    }
}
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

<h2>Edit Soal</h2>

<form method="POST">
    <div class="section">
        <label>Pertanyaan:</label>
        <textarea name="pertanyaan" required><?= htmlspecialchars($soal['pertanyaan']) ?></textarea>
    </div>

    <div class="section">
        <label>Opsi Jawaban dan Bobot:</label>
        <?php foreach (['a', 'b', 'c', 'd', 'e'] as $opt): ?>
            <div class="opsi-bobot">
                <input type="text" name="opsi_<?= $opt ?>" value="<?= htmlspecialchars($soal['opsi_'.$opt]) ?>" required>
                <input type="number" name="bobot_<?= $opt ?>" value="<?= $soal['bobot_'.$opt] ?>" required>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="section">
        <label>Aspek Penilaian:</label>
        <select name="aspek" required>
            <option value="">-- Pilih Aspek --</option>
            <option value="KESEJAHTERAAN PSIKOLOGIS" <?= $soal['aspek'] == 'KESEJAHTERAAN PSIKOLOGIS' ? 'selected' : '' ?>>KESEJAHTERAAN PSIKOLOGIS</option>
            <option value="DUKUNGAN SOSIAL" <?= $soal['aspek'] == 'DUKUNGAN SOSIAL' ? 'selected' : '' ?>>DUKUNGAN SOSIAL</option>
			 <option value="KEPUASAN KERJA" <?= $soal['aspek'] == 'KEPUASAN KERJA' ? 'selected' : '' ?>>KEPUASAN KERJA</option>
        </select>
    </div>

    <button type="submit">Update Soal</button>
</form>

<br>
<a href="input_soal.php">?? Kembali ke Daftar Soal</a>
