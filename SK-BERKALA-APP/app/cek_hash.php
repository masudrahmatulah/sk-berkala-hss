<?php
$password_input = 'admin123';
$hash_tersimpan = '$2y$10$NpSj8y4wqR6d3uV1hQJdUe5vCZQ6u9R2k7YV1fL9i1d2W0s3e2e2O';

if (password_verify($password_input, $hash_tersimpan)) {
    echo "✅ BERHASIL! Hash ini valid untuk password: " . $password_input;
} else {
    echo "❌ GAGAL! Hash tidak cocok.";
}
?>