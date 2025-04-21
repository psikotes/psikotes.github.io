<?php
session_start();
include 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_id = $_POST['nomor_id'];
    $password = $_POST['password'];

    // Query untuk mengecek user dan status tes
    $query = $conn->query("SELECT * FROM users WHERE nomor_id='$nomor_id' AND password='$password'");
    if ($query->num_rows > 0) {
        $user = $query->fetch_assoc();
        
        // Cek apakah status_tes sudah 1 (sudah selesai tes)
        if ($user['status_tes'] == 1) {
            $error = "Anda sudah selesai tes dan tidak bisa login lagi.";
        } else {
            // Jika belum selesai tes, lanjutkan login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            header('Location: tes.php');
            exit();
        }
    } else {
        $error = "Nomor ID atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
             background: url('3.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
            
        }

        .login-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #43a047;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 16px;
        }

        .note {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login Peserta Psikotes</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nomor_id" placeholder="Nomor ID / NRP" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="note">
        Belum punya akun? <a href="daftar.php">Daftar di sini</a>
    </div>
</div>

</body>
</html>
