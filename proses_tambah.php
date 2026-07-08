<?php
session_start();

// Tolak akses jika tidak ada session login admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // Proses Upload Gambar ke Folder Lokal Laragon
    $targetDir = "uploads/";
    
    // Membuat folder uploads otomatis jika belum ada
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES["image"]["name"]); 
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    $allowTypes = array('jpg', 'png', 'jpeg', 'webp');
    
    if (in_array(strtolower($fileType), $allowTypes)) {
        // Pindahkan file ke folder uploads/
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            
            // Query INSERT langsung ke tabel MySQL phpMyAdmin
            try {
                $sql = "INSERT INTO flowers (category_id, name, description, price, image_url) 
                        VALUES (:category_id, :name, :description, :price, :image_url)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':category_id' => $category_id,
                    ':name'        => $name,
                    ':description' => $description,
                    ':price'       => $price,
                    ':image_url'   => $targetFilePath // Menyimpan path lokal seperti "uploads/162546_bunga.jpg"
                ]);
                
                header("Location: admin.php?status=sukses");
                exit;
                
            } catch (PDOException $e) {
                if (file_exists($targetFilePath)) {
                    unlink($targetFilePath); // Hapus gambar jika query gagal
                }
                header("Location: admin.php?status=gagal");
                exit;
            }
        } else {
            header("Location: admin.php?status=gagal");
            exit;
        }
    } else {
        header("Location: admin.php?status=gagal");
        exit;
    }
} else {
    header("Location: admin.php");
    exit;
}
?>