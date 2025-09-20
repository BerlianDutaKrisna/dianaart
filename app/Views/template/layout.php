<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title'); ?> | DianaArt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900">
                <a href="/" class="hover:text-pink-500 transition">
                    <span class="text-pink-500 hover:text-gray-900">Diana</span>Art
                </a>
            </h1>
            <!-- Render Navbar -->
            <?= $this->renderSection('navbar'); ?>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1">
        <?= $this->renderSection('content'); ?>
    </main>

    <!-- Footer -->
    <?= $this->include('template/footer'); ?>

</body>

</html>