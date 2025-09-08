<?php
session_start(); // WAJIB — mulai session

// Jika sudah login, langsung ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $host = 'db';
    $dbname = 'kgb_pns';
    $dbuser = 'userkgb';
    $dbpass = 'passkgb';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login sukses — simpan ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } catch (PDOException $e) {
        $error = "Error sistem: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Aplikasi KGB PNS</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 50px; }
        .login-box {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Aplikasi KGB</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>