<?php
// Di baris paling atas file index.php, panggil koneksi database
require 'koneksi.php';

// Ambil data bunga dari database
$stmt = $pdo->query("SELECT * FROM flowers WHERE is_ready = 1 ORDER BY id DESC");
$flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanaya Florist - Katalog Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        botanical: '#0f3a20', /* Hijau emerald gelap */
                        blush: '#fff0f3',     /* Pink pastel lembut */
                        accent: '#d946ef',    /* Fuchsia cerah untuk tombol */
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blush via-white to-emerald-50 text-slate-800 min-h-screen font-sans">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <span class="text-2xl font-bold bg-gradient-to-r from-botanical to-accent bg-clip-text text-transparent">
                Tanaya Florist
            </span>
            <div class="flex items-center space-x-6 text-sm font-medium">
                <a href="#katalog" class="text-botanical hover:text-accent transition">Katalog</a>
                <a href="#cara-order" class="text-botanical hover:text-accent transition">Cara Order</a>
                
                <a href="login.php" class="text-xs bg-botanical/10 text-botanical hover:bg-botanical hover:text-white px-3 py-1.5 rounded-xl transition-all duration-300 font-semibold tracking-wide border border-botanical/20">
                    Login Admin
                </a>
            </div>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col md:flex-row items-center gap-8">
        <div class="flex-1 space-y-6 text-center md:text-left">
            <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                Fresh & Handcrafted
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-botanical leading-tight">
                Ungkapkan Perasaanmu Lewat <span class="text-accent">Keindahan Bunga</span>
            </h1>
            <p class="text-slate-600 text-base md:text-lg max-w-md mx-auto md:mx-0">
                Katalog buket bunga premium untuk momen kelulusan, pernikahan, maupun dekorasi meja kerja Anda.
            </p>
            <a href="#katalog" class="inline-block bg-gradient-to-r from-fuchsia-600 to-pink-600 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:shadow-xl transition-transform transform hover:-translate-y-0.5">
                Lihat Katalog Bunga
            </a>
        </div>
        <div class="flex-1 w-full max-w-md md:max-w-full">
    <div class="relative h-[350px] md:h-[450px] w-full rounded-3xl shadow-2xl overflow-hidden transform rotate-1 hover:rotate-0 transition-transform duration-500">
        
        <img src="2.jpg" 
             alt="Premium Flower Bouquet 1" 
             class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-100">
        
        <img src="4.jpg" 
             alt="Premium Flower Bouquet 2" 
             class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-0">
        
        <img src="3.jpg" 
             alt="Premium Flower Bouquet 3" 
             class="slide-img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out opacity-0">
             
    </div>
</div>
    </header>

    <main id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center space-y-2 mb-12">
            <h2 class="text-3xl font-bold text-botanical">Koleksi Bunga Terbaik</h2>
            <p class="text-slate-500">Pilih buket favoritmu dan langsung pesan via WhatsApp</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php if (empty($flowers)): ?>
                <div class="col-span-full text-center text-slate-400 py-12">
                    Belum ada bunga yang ditambahkan ke dalam katalog.
                </div>
            <?php else: ?>
                <?php foreach ($flowers as $flower): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition border border-pink-100 flex flex-col">
                    <div class="relative h-72 bg-slate-100 overflow-hidden">
                        <img src="<?= htmlspecialchars($flower['image_url']) ?>" 
                             alt="<?= htmlspecialchars($flower['name']) ?>" 
                             class="w-full h-full object-cover hover:scale-105 transition duration-500">
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-botanical font-bold px-3 py-1 rounded-full text-sm">
                            Rp <?= number_format($flower['price'], 0, ',', '.') ?>
                        </span>
                    </div>
                    <div class="p-6 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-botanical mb-2"><?= htmlspecialchars($flower['name']) ?></h3>
                            <p class="text-slate-500 text-sm mb-4">
                                <?= htmlspecialchars($flower['description']) ?>
                            </p>
                        </div>
                        
                        <?php 
                        // Membuat pesan WhatsApp otomatis sesuai nama produk bunga
                        $pesanWa = "Halo Tanaya Florist, saya ingin memesan *" . $flower['name'] . "* apakah masih tersedia?";
                        $linkWa = "https://wa.me/6281229260315?text=" . urlencode($pesanWa);
                        ?>
                        
                        <a href="<?= $linkWa ?>" 
                           target="_blank" 
                           class="w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 rounded-xl inline-flex items-center justify-center gap-2 transition shadow-md">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.4.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.457L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.966C16.528 1.975 14.062 1.053 11.45 1.053c-5.44 0-9.866 4.372-9.87 9.802 0 1.714.453 3.39 1.31 4.877L1.87 21.083l5.635-1.478z"/>
                            </svg>
                            Pesan via WhatsApp
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </main>

<section id="cara-order" class="bg-white/60 backdrop-blur-sm py-16 border-t border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-2 mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-accent bg-fuchsia-100 px-3 py-1 rounded-full">
                    Gampang Banget!
                </span>
                <h2 class="text-3xl font-bold text-botanical">Cara Order (Anti Ribet ✨)</h2>
                <p class="text-slate-500 text-sm">Nggak pakai drama, pesan buket impianmu lewat 4 langkah gampang ini:</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-pink-50 flex flex-col items-center text-center space-y-3 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-xl text-emerald-700 font-bold shadow-inner">
                        🔍
                    </div>
                    <h3 class="font-bold text-botanical text-lg">1. Pilih Vibes-Mu</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Scroll katalog cantik di atas. Pilih varian buket bunga yang paling match sama momen atau orang tersayangmu.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-pink-50 flex flex-col items-center text-center space-y-3 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-fuchsia-50 rounded-xl flex items-center justify-center text-xl text-fuchsia-700 font-bold shadow-inner">
                        📱
                    </div>
                    <h3 class="font-bold text-botanical text-lg">2. Klik Pesan</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Tekan tombol <span class="font-semibold text-emerald-600">Pesan via WhatsApp</span>. Sistem kami otomatis buatin template teks chat, jadi kamu tinggal kirim aja!
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-pink-50 flex flex-col items-center text-center space-y-3 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-xl text-amber-700 font-bold shadow-inner">
                        💳
                    </div>
                    <h3 class="font-bold text-botanical text-lg">3. Deal & Payment</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Admin kami bakal gercep (gerak cepat) balas chatmu. Isi format pesanan, lakukan pembayaran via transfer/E-Wallet, dan selesai!
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-pink-50 flex flex-col items-center text-center space-y-3 hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-xl text-blue-700 font-bold shadow-inner">
                        🚀
                    </div>
                    <h3 class="font-bold text-botanical text-lg">4. Buket Meluncur</h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Buket bungamu bakal dirangkai dengan rapi dan dikirim langsung ke alamat tujuan atau siap kamu pickup. Tinggal duduk manis deh!
                    </p>
                </div>

            </div>

        </div>
    </section>

    <footer class="bg-botanical text-pink-100 py-12 text-center text-sm border-t border-emerald-800">
        <p>&copy; 2026 Tanaya Florist. All Rights Reserved. Crafted beautifully for mobile and desktop.</p>
    </footer>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide-img');
        const totalSlides = slides.length;

        function nextSlide() {
            // 1. Sembunyikan gambar yang sekarang aktif (Ubah dari terlihat jadi transparan)
            slides[currentSlide].classList.remove('opacity-100');
            slides[currentSlide].classList.add('opacity-0');
            
            // 2. Hitung indeks gambar berikutnya (kalau sudah habis kembali ke 0)
            currentSlide = (currentSlide + 1) % totalSlides;
            
            // 3. Tampilkan gambar berikutnya (Ubah dari transparan jadi terlihat)
            slides[currentSlide].classList.remove('opacity-0');
            slides[currentSlide].classList.add('opacity-100');
        }

        // Jalankan fungsi nextSlide otomatis setiap 3.5 detik (3500 milidetik)
        setInterval(nextSlide, 5500);
    </script>

</body>
</html>