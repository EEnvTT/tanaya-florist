<?php
session_start();

// Jika admin sudah login sebelumnya, langsung lempar ke halaman admin
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit;
}

// Pengaturan Username & Password Admin (Silakan diganti sesuai keinginan)
$admin_user = "admin";
$admin_pass = "admin123"; // Ganti dengan password yang lebih aman

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        // Jika cocok, buat session login
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Tanaya Florist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { botanical: '#0f3a20', blush: '#fff0f3', accent: '#d946ef' } } }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blush via-white to-emerald-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-botanical">Tanaya Florist</h1>
            <p class="text-slate-400 text-sm mt-1">Masuk ke Dashboard Admin</p>
        </div>

        <?php if ($error): ?>
            <div class="mb-4 p-3 bg-rose-100 text-rose-800 rounded-xl text-sm font-medium text-center">
                Username atau Password salah!
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" required autocomplete="off"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
            </div>

            <button type="submit" class="w-full bg-botanical hover:bg-emerald-900 text-white font-medium py-3 rounded-xl shadow-md transition transform active:scale-95">
                Masuk Sekarang
            </button>
        </form>
        
        <div class="text-center mt-6">
            <a href="index.php" class="text-xs text-slate-400 hover:text-botanical transition">&larr; Kembali ke Toko</a>
        </div>
    </div>

</body>
</html>