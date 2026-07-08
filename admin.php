<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

// Ambil semua data bunga dari database
$stmt = $pdo->query("SELECT * FROM flowers ORDER BY id DESC");
$flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Tanaya Florist</title>
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
            <span class="text-xl font-bold">Tanaya Florist <span class="text-xs bg-emerald-700 px-2 py-1 rounded-md ml-2">Admin Panel</span></span>
            <div class="flex items-center gap-4">
                <a href="index.php" target="_blank" class="text-sm bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg transition">Lihat Toko</a>
                <a href="logout.php" class="text-sm bg-rose-600 hover:bg-rose-700 px-4 py-2 rounded-lg transition font-medium">Logout</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Notifikasi Pesan Aksi -->
        <?php if ($status === 'added'): ?>
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-xl text-sm font-medium">
                Berhasil menambahkan bunga baru!
            </div>
        <?php elseif ($status === 'updated'): ?>
            <div class="mb-6 p-4 bg-blue-100 text-blue-800 rounded-xl text-sm font-medium">
                Data bunga berhasil diperbarui!
            </div>
        <?php elseif ($status === 'deleted'): ?>
            <div class="mb-6 p-4 bg-rose-100 text-rose-800 rounded-xl text-sm font-medium">
                Data bunga telah dihapus!
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Tambah Bunga Baru -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-slate-200 h-fit">
                <h2 class="text-xl font-bold text-botanical mb-4">Tambah Bunga Baru</h2>
                <form action="proses_tambah.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Bunga</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Harga (Rp)</label>
                        <input type="number" name="price" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Foto Bunga</label>
                        <input type="file" name="image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_ready" id="is_ready_add" value="1" checked class="w-4 h-4 text-emerald-600 rounded">
                        <label for="is_ready_add" class="text-xs text-slate-600 font-medium">Tampilkan di Katalog (Ready Stock)</label>
                    </div>
                    <button type="submit" class="w-full bg-botanical hover:bg-emerald-900 text-white font-medium py-2.5 rounded-xl text-sm transition shadow-sm">
                        + Tambah Produk
                    </button>
                </form>
            </div>

            <!-- Tabel Daftar Bunga (Fitur Edit & Hapus) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-md border border-slate-200">
                <h2 class="text-xl font-bold text-botanical mb-4">Daftar Katalog Bunga</h2>
                
                <?php if (empty($flowers)): ?>
                    <p class="text-slate-400 text-sm text-center py-8">Belum ada data bunga.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50 text-slate-600 text-xs uppercase">
                                    <th class="p-3">Foto</th>
                                    <th class="p-3">Nama</th>
                                    <th class="p-3">Harga</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($flowers as $f): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3">
                                        <img src="<?= htmlspecialchars($f['image_url']) ?>" alt="Bunga" class="w-12 h-12 object-cover rounded-lg border">
                                    </td>
                                    <td class="p-3 font-semibold text-slate-800"><?= htmlspecialchars($f['name']) ?></td>
                                    <td class="p-3 text-slate-600">Rp <?= number_format($f['price'], 0, ',', '.') ?></td>
                                    <td class="p-3">
                                        <?php if ($f['is_ready']): ?>
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Ready</span>
                                        <?php else: ?>
                                            <span class="bg-slate-100 text-slate-500 text-xs font-semibold px-2.5 py-0.5 rounded-full">Sembunyi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Tombol Edit -->
                                            <a href="edit.php?id=<?= $f['id'] ?>" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                Edit
                                            </a>
                                            <!-- Tombol Hapus dengan Konfirmasi -->
                                            <a href="hapus.php?id=<?= $f['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?= htmlspecialchars(addslashes($f['name'])) ?>?');" class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </main>

</body>
</html>