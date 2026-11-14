
<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$id = filter_var(
    $_POST['id'] ?? '',
    FILTER_VALIDATE_INT
);

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');

if (
    !$id ||
    $id < 1 ||
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

    // Pastikan data yang akan diubah tersedia
    $check = $pdo->prepare(
        "SELECT id FROM users WHERE id = :id"
    );

    $check->execute([':id' => $id]);

    if (!$check->fetch()) {
        header('Location: index.php?status=not_found');
        exit();
    }

    // Proses UPDATE
    $sql = "UPDATE users
            SET username = :username,
                email = :email
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':id' => $id
    ]);

    header('Location: index.php?status=updated');
    exit();

} catch (PDOException $e) {
    error_log($e->getMessage());

    header('Location: index.php?status=error');
    exit();
}