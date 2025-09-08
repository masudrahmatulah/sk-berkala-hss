<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak. Silakan login dulu.");
}
?>
<?php
$host = 'db';
$dbname = 'kgb_pns';
$username = 'userkgb';
$password = 'passkgb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $sql = "INSERT INTO pns (nip, nama, pangkat_golongan, tmt_pangkat, masa_kerja_golongan, gaji_pokok_terakhir, unit_kerja, tgl_lahir, tmt_cpns) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nip'],
        $_POST['nama'],
        $_POST['pangkat_golongan'],
        $_POST['tmt_pangkat'],
        $_POST['masa_kerja_golongan'],
        $_POST['gaji_pokok_terakhir'],
        $_POST['unit_kerja'] ?? null,
        $_POST['tgl_lahir'] ?? null,
        $_POST['tmt_cpns'] ?? null
    ]);

    echo "Data berhasil disimpan! <a href='index.php'>Kembali</a>";

} catch (PDOException $e) {
    die("Gagal simpan: " . $e->getMessage());
}