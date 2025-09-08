<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi KGB PNS</title>
    <meta charset="utf-8">
</head>
<body>
    <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mk_bulan = $_POST['masa_kerja_golongan'];
    $golongan = $_POST['pangkat_golongan'];

    // Contoh aturan: Golongan I & II = minimal 24 bulan, III & IV = 36 bulan
    $layak = false;
    if (strpos($golongan, 'I') !== false || strpos($golongan, 'II') !== false) {
        $layak = $mk_bulan >= 24;
    } elseif (strpos($golongan, 'III') !== false || strpos($golongan, 'IV') !== false) {
        $layak = $mk_bulan >= 36;
    }

    if ($layak) {
        echo "<p style='color:green;'>✅ LAYAK KGB!</p>";
    } else {
        echo "<p style='color:red;'>❌ BELUM LAYAK KGB. Masa kerja kurang.</p>";
    }
}
?>
<div style="text-align: right; padding: 10px; background: #eee;">
        Halo, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> 
        | <a href="ganti_password.php">Ganti Password</a>
        | <a href="logout.php">Logout</a>
    <h2>Input Data PNS untuk KGB</h2>
    <form method="POST" action="simpan.php">
        NIP: <input type="text" name="nip" required><br><br>
        Nama: <input type="text" name="nama" required><br><br>
        Pangkat/Golongan: <input type="text" name="pangkat_golongan" required><br><br>
        TMT Pangkat: <input type="date" name="tmt_pangkat" required><br><br>
        Masa Kerja Golongan (bulan): <input type="number" name="masa_kerja_golongan" required><br><br>
        Gaji Pokok Terakhir: <input type="number" name="gaji_pokok_terakhir" required><br><br>
        Unit Kerja: <input type="text" name="unit_kerja"><br><br>
        Tanggal Lahir: <input type="date" name="tgl_lahir"><br><br>
        TMT CPNS: <input type="date" name="tmt_cpns"><br><br>
        <button type="submit">Simpan</button>
    </form>

    <hr>
        <h3>Data PNS yang Sudah Diinput:</h3>
    <?php
    $host = 'db';
    $dbname = 'kgb_pns';
    $username = 'userkgb';
    $password = 'passkgb';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $stmt = $pdo->query("SELECT * FROM pns ORDER BY created_at DESC");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>NIP</th><th>Nama</th><th>Golongan</th><th>Gaji</th><th>MK Gol</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nip']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pangkat_golongan']) . "</td>";
            echo "<td>Rp " . number_format($row['gaji_pokok_terakhir'],0,',','.') . "</td>";
            echo "<td>" . $row['masa_kerja_golongan'] . " bulan</td>";
            echo "<td>... <a href='cetak_sk.php?id=" . $row['id'] . "'>Cetak SK</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "Belum ada data atau error koneksi: " . $e->getMessage();
    }
    ?>
</body>
</html>