
<?php

require_once 'db_connect.php';

// Pastikan permintaan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Validasi ID pengguna
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?status=invalid_id');
    exit();
}

try {

    $pdo = connect_db();

    // Hapus data berdasarkan ID menggunakan Prepared Statement
    $sql = "DELETE FROM users WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    // Periksa apakah ada data yang benar-benar dihapus
    if ($stmt->rowCount() > 0) {
        header('Location: index.php?status=deleted');
    } else {
        header('Location: index.php?status=not_found');
    }

    exit();

} catch (PDOException $e) {

    error_log($e->getMessage());

    header('Location: index.php?status=error');
    exit();
}
