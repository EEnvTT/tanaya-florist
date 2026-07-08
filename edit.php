<?php
session_start();

// Proteksi session admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$error = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admin.php");
    exit;
}

// Ambil data bunga berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM flowers WHERE id = ?");
$stmt->execute([$id]);
$flower = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flower) {
    header("Location: admin.php");
    exit;
}

// Proses Form Update Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $description = trim($_POST['description']);
    $is_ready = isset($_POST['is_ready']) ? 1 : 0;
    $image_url = $flower['image_url']; // Default gunakan gambar lama

    // Cek apakah ada file gambar baru yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = 'uploads/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Hapus gambar lama jika ada
                if (!empty($flower['image_url']) && file_exists($flower['image_url'])) {
                    unlink($flower['image_url']);
                }
                $image_url = $dest_path;
            } else {
                $error = 'Gagal mengunggah gambar baru.';
            }
        } else {
            $error = 'Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.';
        }
    }

    if (empty($error)) {
        $updateStmt = $pdo->prepare("UPDATE flowers SET name = ?, price = ?, description = ?, image_url = ?, is_ready = ? WHERE id = ?");
        $updateStmt->execute([$name, $price, $description, $image_url, $is_ready, $id]);

        header("Location: admin.php?status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bunga - Tanaya Florist Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        botanical: '#0f3a20',
                        blush: '#fff0f3',
                        accent: '#d946ef',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">

    <!-- Navbar Admin -->
    <nav class="bg-botanical text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <span class="text-xl font-bold">Tanaya Florist Admin</span>
            <a href="admin.php" class="text-sm bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">&larr; Kembali ke Dashboard</a>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white p-8 rounded-2xl shadow-md border border-slate-200">
            <h1 class="text-2xl font-bold text-botanical mb-6">Edit Data Bunga</h1>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 bg-rose-100 text-rose-800 rounded-xl text-sm font-medium">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bunga / Buket</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($flower['name']) ?>" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($flower['price']) ?>" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" required
                              class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"><?= htmlspecialchars($flower['description']) ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Bunga Saat Ini</label>
                    <div class="mb-3 w-32 h-32 rounded-xl overflow-hidden border border-slate-200">
                        <img src="<?= htmlspecialchars($flower['image_url']) ?>" alt="Gambar Bunga" class="w-full h-full object-cover">
                    </div>
                    <label class="block text-xs text-slate-500 mb-1">Ganti Gambar (Biarkan kosong jika tidak ingin mengubah gambar):</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_ready" id="is_ready" value="1" <?= $flower['is_ready'] ? 'checked' : '' ?>
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 border-slate-300">
                    <label for="is_ready" class="text-sm font-medium text-slate-700">Tampilkan di Katalog Utama (Ready Stock)</label>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t">
                    <a href="admin.php" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-botanical hover:bg-emerald-900 text-white text-sm font-medium shadow-md transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>