<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->renderSection('title'); ?> | DianaArt
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('img/Dianaart.png'); ?>">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 flex items-center space-x-2">
                <a href="/" class="flex items-center hover:text-pink-500 transition">
                    <!-- Logo -->
                    <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="h-8 w-8 mr-2">
                    <!-- Text -->
                    <span class="text-pink-500 hover:text-gray-900">Diana</span>Art
                </a>
            </h1>
            <!-- Render Navbar -->
            <?= $this->renderSection('navbar'); ?>
        </div>
    </header>

    <!-- Content -->
    <main class="py-8">
        <?= $this->renderSection('content'); ?>
    </main>

    <!-- Footer -->
    <?= $this->include('template/footer'); ?>
</body>

</html>