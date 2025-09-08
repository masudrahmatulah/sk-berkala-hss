<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<?php
$id = $_GET['id'] ?? 0;
if (!$id) die("ID tidak ditemukan");

$host = 'db';
$dbname = 'kgb_pns';
$username = 'userkgb';
$password = 'passkgb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $stmt = $pdo->prepare("SELECT * FROM pns WHERE id = ?");
    $stmt->execute([$id]);
    $pns = $stmt->fetch();

    if (!$pns) die("Data tidak ditemukan");

    // Simulasi hitung gaji baru (naik 1 tingkat)
    $gaji_baru = $pns['gaji_pokok_terakhir'] * 1.05; // naik 5% contoh

    // TMT KGB = 1 April atau 1 Oktober tahun ini
    $bulan_ini = date('n');
    $tahun_ini = date('Y');
    $tmt_kgb = ($bulan_ini <= 4) ? "$tahun_ini-04-01" : "$tahun_ini-10-01";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SK KGB - <?php echo $pns['nama']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .kop { text-align: center; font-weight: bold; margin-bottom: 30px; }
        .isi { line-height: 1.8; }
        .ttd { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="kop">
        SURAT KEPUTUSAN<br>
        KENAIKAN GAJI BERKALA<br>
        NOMOR: KGB/001/<?php echo date('Y'); ?>
    </div>

    <div class="isi">
        <p>Berdasarkan ketentuan peraturan perundang-undangan yang berlaku, dengan ini:</p>

        <p>Memberikan Kenaikan Gaji Berkala kepada:</p>
        <p>Nama: <strong><?php echo $pns['nama']; ?></strong></p>
        <p>NIP: <strong><?php echo $pns['nip']; ?></strong></p>
        <p>Pangkat/Golongan: <strong><?php echo $pns['pangkat_golongan']; ?></strong></p>
        <p>Gaji Pokok Lama: <strong>Rp <?php echo number_format($pns['gaji_pokok_terakhir'],0,',','.'); ?></strong></p>
        <p>Gaji Pokok Baru: <strong>Rp <?php echo number_format($gaji_baru,0,',','.'); ?></strong></p>
        <p>Terhitung Mulai Tanggal: <strong><?php echo date('d F Y', strtotime($tmt_kgb)); ?></strong></p>
    </div>

    <div class="ttd">
        <p>Pejabat Berwenang,</p>
        <br><br><br>
        <p>____________________</p>
    </div>

    <button onclick="window.print()">🖨️ Cetak SK</button>
</body>
</html>