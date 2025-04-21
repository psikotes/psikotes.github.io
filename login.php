<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek username dan password
    $query = $conn->query("SELECT * FROM admins WHERE username='$username' AND password='$password'");

    if ($query->num_rows > 0) {
        $admin = $query->fetch_assoc();

        // Simpan ke session
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        // Arahkan ke dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<p style='color:red;'>Login gagal! Username atau password salah.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #4CAF50, #2e7d32);  /* Gradient hijau */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Kontainer login */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        /* Kotak login */
        .login-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-box h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* Inputan */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Tombol login */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Pesan kesalahan */
        p {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>Login Admin</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</div>

</body>
</html>
