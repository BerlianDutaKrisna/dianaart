<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Opsi konfigurasi Tailwind via CDN (boleh dikosongkan)
        tailwind.config = {
            theme: {
                extend: {}
            }
        }
    </script>

    <title><?= $this->renderSection('title') ?: 'DianaArt' ?></title>

    <!-- Meta ekstra dari child view -->
    <?= $this->renderSection('head') ?>
</head>

<body class="bg-white antialiased">

    <!-- Elements: aktifkan <el-dialog> & command="show-modal" -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <!-- Header -->
    <header class="absolute inset-x-0 top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 flex items-center">
                <a href="<?= base_url('/') ?>" class="flex items-center hover:text-pink-500 transition">
                    <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="h-8 w-8 mr-2">
                    <span class="text-pink-500">Diana</span>Art
                </a>
            </h1>

            <!-- Section navbar: diisi dari child view -->
            <?= $this->renderSection('navbar'); ?>
        </div>
    </header>

    <!-- Konten utama -->
    <main class="py-8">
        <?= $this->renderSection('content'); ?>
    </main>

    <!-- Footer sederhana (opsional: ganti dengan include jika punya file footer terpisah) -->
    <footer class="border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-gray-500 flex justify-between items-center">
            <p>© <?= date('Y') ?> DianaArt. All rights reserved.</p>
            <div class="space-x-4">
                <a href="#" class="hover:text-gray-700">Privacy</a>
                <a href="#" class="hover:text-gray-700">Terms</a>
            </div>
        </div>
    </footer>

    <!-- Script tambahan dari child view -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>