<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Dashboard Admin</h2>
            <p>Selamat datang, <strong><?php echo $_SESSION['admin_username']; ?></strong>!</p>
        </div>

        <div class="dashboard-menu">
            <ul>
                <li><a href="input_soal.php" class="menu-item">
                    <span class="material-icons">edit</span> Input Soal
                </a></li>
                <li><a href="hasil_tes.php" class="menu-item">
                    <span class="material-icons">assessment</span> Lihat Hasil Tes
                </a></li>
				<li><a href="atur_aspek.php" class="menu-item">
                    <span class="material-icons">assessment</span> Pilih Tes 
                </a></li>
				<li><a href="cetak_form.php" class="menu-item">
                    <span class="material-icons">assessment</span> Cetak Berdasarkan Nrp
                </a></li>
                <li><a href="logout.php" class="menu-item logout">
                    <span class="material-icons">exit_to_app</span> Logout
                </a></li>
            </ul>
        </div>
    </div>

</body>
</html>
