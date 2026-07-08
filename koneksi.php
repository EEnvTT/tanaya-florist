<?php
$host = 'sql308.infinityfree.com'; // ganti dengan Hostname dari hosting
$db   = 'if0_42360869_tanaya_florist'; // ganti nama database hosting
$user = 'if0_42360869';                // ganti username hosting
$pass = 'WHsl70IqOZ170';   // ganti password hosting

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
