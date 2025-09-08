<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "<h2>Salin hash berikut ke database:</h2>";
echo "<code style='background:#eee; padding:10px; display:block;'>" . $hash . "</code>";
echo "<br><br>";
echo "Password: <strong>" . $password . "</strong><br>";
echo "Cek kekuatan hash: " . (password_verify($password, $hash) ? "✅ Valid" : "❌ Invalid");
?>