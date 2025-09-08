<?php
session_start();

// Proteksi: hanya yang sudah login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Ambil koneksi database
$host = 'db';
$dbname = 'kgb_pns';
$dbuser = 'userkgb';
$dbpass = 'passkgb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Proses form jika di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    // Validasi: jangan biarkan kosong
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        $error = "Semua kolom harus diisi!";
    }
    // Validasi: password baru dan konfirmasi harus sama
    elseif ($password_baru !== $konfirmasi_password) {
        $error = "Password baru dan konfirmasi tidak cocok!";
    }
    else {
        // Ambil data user dari database
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user && password_verify($password_lama, $user['password'])) {
            // Password lama benar → hash password baru
            $hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

            // Update ke database
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hash_baru, $_SESSION['user_id']]);

            $success = "✅ Password berhasil diubah! Silakan login kembali.";
            // Logout otomatis setelah ganti password (opsional, tapi disarankan)
            session_destroy();
            header("Refresh: 3; url=login.php"); // redirect ke login setelah 3 detik
            exit;
        } else {
            $error = "Password lama salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password - Aplikasi KGB PNS</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .form-box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #218838;
        }
        .error {
            color: red;
            padding: 10px;
            background: #ffecec;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            color: green;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 5px;
            margin: 10px 0;
        }
        .back {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>🔐 Ganti Password</h2>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
        <form method="POST">
            <input type="password" name="password_lama" placeholder="Password Lama" required>
            <input type="password" name="password_baru" placeholder="Password Baru" required>
            <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password Baru" required>
            <button type="submit" class="btn">Ubah Password</button>
        </form>
        <?php endif; ?>

        <a href="index.php" class="back">← Kembali ke Dashboard</a>
    </div>
</body>
</html>