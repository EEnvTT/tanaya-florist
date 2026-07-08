<?php
session_start();

// Proteksi session admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data gambar terlebih dahulu untuk menghapus file fisik di folder uploads
    $stmt = $pdo->prepare("SELECT image_url FROM flowers WHERE id = ?");
    $stmt->execute([$id]);
    $flower = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($flower) {
        // Hapus file gambar fisik jika ada
        if (!empty($flower['image_url']) && file_exists($flower['image_url'])) {
            unlink($flower['image_url']);
        }

        // Hapus record dari database
        $deleteStmt = $pdo->prepare("DELETE FROM flowers WHERE id = ?");
        $deleteStmt->execute([$id]);
    }
}

header("Location: admin.php?status=deleted");
exit;