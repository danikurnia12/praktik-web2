
<?php

require_once 'db_connect.php';

// Pastikan data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Mengambil dan membersihkan spasi input
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validasi server-side
if (
    $username === '' ||
    $email === '' ||
    strlen($username) > 50 ||
    strlen($email) > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    header('Location: index.php?status=invalid');
    exit();
}

try {

    $pdo = connect_db();

    // Prepared Statement untuk mencegah SQL Injection
    $sql = "INSERT INTO users (username, email)
            VALUES (:username, :email)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':username' => $username,
        ':email' => $email
    ]);

    // Kembali ke halaman utama
    header('Location: index.php?status=success');
    exit();

} catch (PDOException $e) {

    // Detail error dicatat untuk programmer
    error_log($e->getMessage());

    // Pesan umum ditampilkan kepada pengguna
    header('Location: index.php?status=error');
    exit();
}
