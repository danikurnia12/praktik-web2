
<?php
require_once 'db_connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?status=invalid_id');
    exit();
}

try {
    $pdo = connect_db();

    $stmt = $pdo->prepare(
        "SELECT id, username, email FROM users WHERE id = :id"
    );

    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: index.php?status=not_found');
        exit();
    }

} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Terjadi kesalahan saat mengambil data.');
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna</title>
</head>
<body>

    <h2>Form Edit Pengguna</h2>

    <form action="update_user.php" method="POST">

        <input type="hidden" name="id"
               value="<?= (int)$user['id'] ?>">

        <label>Username:</label><br>
        <input type="text" name="username"
               value="<?= e($user['username']) ?>"
               maxlength="50" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email"
               value="<?= e($user['email']) ?>"
               maxlength="100" required>
        <br><br>

        <button type="submit">Simpan Perubahan</button>

        <a href="index.php">Batal</a>

    </form>

</body>
</html>
