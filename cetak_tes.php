<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_user = $_SESSION['user_id'];

// Ambil aspek aktif dari pengaturan
$getAspek = $conn->query("SELECT aspek_aktif FROM pengaturan LIMIT 1");
$rowAspek = $getAspek->fetch_assoc();
$aspekTerpilih = $rowAspek['aspek_aktif'];

// Jika admin memilih "Semua Soal", tampilkan semua soal dari ketiga aspek
if ($aspekTerpilih == 'Semua Soal') {
    $soals = $conn->query("SELECT * FROM soals");
} else {
    // Ambil soal berdasarkan aspek yang dipilih admin
    $soals = $conn->query("SELECT * FROM soals WHERE aspek = '$aspekTerpilih'");
}

// Proses jika user mengirim jawaban
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $id_soal => $jawaban) {
        if ($id_soal == "submit_test") continue; // Skip tombol

        $get = $conn->query("SELECT bobot_$jawaban AS nilai FROM soals WHERE id = $id_soal");
        $data = $get->fetch_assoc();
        $nilai = $data['nilai'];

        // Simpan jawaban
        $conn->query("INSERT INTO jawaban (id_user, id_soal, jawaban, nilai)
                      VALUES ($id_user, $id_soal, '$jawaban', $nilai)");
    }

    // Update status_tes menjadi 1 setelah tes selesai
    $conn->query("UPDATE users SET status_tes = 1 WHERE id = $id_user");

    // Arahkan ke halaman hasil tes
    echo "<script>window.location.href='userlogin.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tes Psikotes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #003366;
            margin-bottom: 30px;
        }

        /* Petunjuk Tes */
        .petunjuk {
            background-color: #ffffff;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 10px;
            border-left: 5px solid #0072ff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .petunjuk h3 {
            font-size: 1.8rem;
            font-weight: bold;
            color: #333333;
            margin-bottom: 15px;
        }

        .petunjuk p {
            font-size: 1.1rem;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .petunjuk p strong {
            color: #0072ff;
        }

        /* Timer */
        #timer {
            font-size: 1.5rem;
            font-weight: bold;
            color: red;
            margin: 20px 0;
            text-align: center;
        }

        /* Desain soal */
        .soal {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Tombol */
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 25px;
            font-size: 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Menyembunyikan soal container sebelum dimulai */
        #soal-container {
            display: none;
        }

        /* Mengatur elemen form dan input dengan desain bersih */
        input[type="radio"] {
            margin-right: 10px;
        }

        input[type="radio"]:checked {
            background-color: #0072ff;
        }

        /* Responsif untuk tampilan mobile */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .petunjuk {
                padding: 20px;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h2>Selamat datang di Tes Psikologi Prajurit TNI AL P/D-4</h2>

<!-- Petunjuk Tes -->
<div class="petunjuk">
    <h3>Petunjuk Pengisian Kuesioner</h3>
    <p><strong>Jawaban yang Jujur:</strong> Pilih jawaban yang paling menggambarkan kondisi Anda saat ini. Semua jawaban dijaga kerahasiaannya dan hanya digunakan untuk tujuan penelitian.</p>
    <p><strong>Tidak Ada Jawaban yang Salah:</strong> Tes ini bertujuan untuk memahami kepribadian dan kondisi psikologis Anda. Pilih jawaban yang paling sesuai dengan diri Anda.</p>
    <p><strong>Waktu Pengisian:</strong> Anda memiliki waktu 1 jam untuk menyelesaikan tes. Pastikan menjawab dengan hati-hati, waktu akan dihitung otomatis.</p>
    <p><strong>Kerahasiaan Data:</strong> Semua data yang Anda berikan selama tes akan dirahasiakan dan digunakan hanya untuk evaluasi internal.</p>
    <p><strong>Terima kasih atas partisipasi Anda..</strong></p>
</div>

<!-- Timer -->
<div id="timer">Waktu: 01:00</div>

<!-- Tombol untuk mulai -->
<button id="startBtn">Mulai Tes</button>

<form method="POST" id="formTes">
    <div id="soal-container">
        <?php
        $no = 1;
        $soals->data_seek(0);
        while ($row = $soals->fetch_assoc()) {
            echo "<div class='soal'>";
            echo "<p><b>$no. {$row['pertanyaan']}</b></p>";
            foreach (['a', 'b', 'c', 'd', 'e'] as $opt) {
                $opsi = htmlspecialchars($row["opsi_$opt"]);
                echo "<label><input type='radio' name='{$row['id']}' value='$opt' required> $opsi</label><br>";
            }
            echo "</div>";
            $no++;
        }
        ?>
        <input type="hidden" name="submit_test" value="1">
        <button type="submit">Selesai Tes</button>
    </div>
</form>

<!-- Tombol Cetak -->
<button onclick="window.print();" style="background-color: #0072ff;">Cetak Tes</button>

<script>
    const startBtn = document.getElementById("startBtn");
    const soalContainer = document.getElementById("soal-container");
    const timerEl = document.getElementById("timer");
    const formTes = document.getElementById("formTes");

    let waktu = 3600; // waktu dalam detik

    function mulaiTimer() {
        timerEl.style.display = 'block';
        let countdown = setInterval(function () {
            let menit = Math.floor(waktu / 60);
            let detik = waktu % 60;
            timerEl.textContent = `Waktu: ${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;
            waktu--;

            if (waktu < 0) {
                clearInterval(countdown);
                formTes.submit(); // Kirim otomatis saat waktu habis
            }
        }, 1000);
    }

    startBtn.addEventListener("click", () => {
        startBtn.style.display = "none";
        soalContainer.style.display = "block";
        mulaiTimer();
    });
</script>

</body>
</html>
