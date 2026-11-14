
<?php

require_once 'db_connect.php';

try {

    $conn = connect_db();

    echo "<h2>Koneksi Database Berhasil!</h2>";
    echo "<p>PHP berhasil terhubung dengan MySQL.</p>";
    echo "<p>Database: web_dev_db</p>";

    $conn = null;

} catch (PDOException $e) {

    echo "<h2>Koneksi Database Gagal!</h2>";
    echo "<p>Periksa konfigurasi database dan pastikan MySQL berjalan.</p>";
}
