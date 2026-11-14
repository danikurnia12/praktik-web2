
<?php
require_once 'db_connect.php';

$pdo = connect_db();

// Mengambil data pengguna (READ)
$stmt = $pdo->query(
    "SELECT id, username, email, created_at
     FROM users
     ORDER BY id DESC"
);

$users = $stmt->fetchAll();

// Fungsi keamanan untuk output HTML
function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Mengambil status notifikasi
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pengguna</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f4f6f9;
        }

        .container {
            max-width: 950px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        h2 {
            color: #1f2937;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Input tersembunyi untuk ID */
        input[type="hidden"] {
            display: none;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.85;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 11px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #e5e7eb;
            color: #1f2937;
        }

        tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .success {
            color: #15803d;
            background: #dcfce7;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .error {
            color: #b91c1c;
            background: #fee2e2;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .action-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
            margin-right: 8px;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .delete-form {
            display: inline;
            margin: 0;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .btn-delete:hover {
            background: #991b1b;
        }

        .btn-save {
            background: #2563eb;
        }

        .empty-data {
            text-align: center;
            color: #6b7280;
            padding: 20px;
        }

        @media (max-width: 700px) {
            body {
                margin: 10px;
            }

            .container {
                padding: 15px;
                overflow-x: auto;
            }

            table {
                min-width: 650px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Form Input Pengguna</h2>

    <!-- NOTIFIKASI SISTEM -->

    <?php if ($status === 'success'): ?>

        <p class="success">
            Data berhasil disimpan!
        </p>

    <?php elseif ($status === 'updated'): ?>

        <p class="success">
            Data berhasil diperbarui!
        </p>

    <?php elseif ($status === 'deleted'): ?>

        <p class="success">
            Data berhasil dihapus!
        </p>

    <?php elseif ($status === 'invalid'): ?>

        <p class="error">
            Username atau email tidak valid.
        </p>

    <?php elseif ($status === 'not_found'): ?>

        <p class="error">
            Data tidak ditemukan.
        </p>

    <?php elseif ($status === 'invalid_id'): ?>

        <p class="error">
            ID pengguna tidak valid.
        </p>

    <?php elseif ($status === 'error'): ?>

        <p class="error">
            Terjadi kesalahan. Data gagal diproses.
        </p>

    <?php endif; ?>


    <!-- FORM INPUT PENGGUNA -->

    <form action="store_user.php" method="POST">

        <label>Username:</label>

        <input
            type="text"
            name="username"
            maxlength="50"
            required
            placeholder="Masukkan username"
        >

        <label>Email:</label>

        <input
            type="email"
            name="email"
            maxlength="100"
            required
            placeholder="Masukkan email"
        >

        <button type="submit" class="btn-save">
            Simpan Data
        </button>

    </form>

    <hr>

    <!-- TABEL DATA PENGGUNA -->

    <h2>Daftar Pengguna</h2>

    <table>

        <thead>
            <tr>
                <th>No.</th>
                <th>Username</th>
                <th>Email</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

        <?php if (count($users) > 0): ?>

            <?php $no = 1; ?>

            <?php foreach ($users as $user): ?>

                <tr>

                    <td>
                        <?= $no++ ?>
                    </td>

                    <td>
                        <?= e($user['username']) ?>
                    </td>

                    <td>
                        <?= e($user['email']) ?>
                    </td>

                    <td>
                        <?= e($user['created_at']) ?>
                    </td>

                    <!-- TOMBOL AKSI EDIT DAN HAPUS -->

                    <td>

                        <a
                            href="edit_user.php?id=<?= (int)$user['id'] ?>"
                            class="action-link"
                        >
                            Edit
                        </a>

                        <form
                            action="delete_user.php"
                            method="POST"
                            class="delete-form"
                            onsubmit="return confirmDelete();"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int)$user['id'] ?>"
                            >

                            <button
                                type="submit"
                                class="btn-delete"
                            >
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="5" class="empty-data">
                    Belum ada data pengguna.
                </td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>


<!-- JAVASCRIPT KONFIRMASI PENGHAPUSAN -->

<script>
function confirmDelete() {

    return confirm(
        "Apakah kamu yakin ingin menghapus data ini?\n\n" +
        "Data yang dihapus tidak dapat dikembalikan!"
    );

}
</script>

</body>
</html>