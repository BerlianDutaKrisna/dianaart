<!doctype html>
<html lang="id" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // Opsional: konfigurasi tambahan Tailwind
        tailwind.config = {
            theme: {
                extend: {}
            }
        }
    </script>

    <title><?= $this->renderSection('title') ?: 'Dashboard — DianaArt' ?></title>

    <!-- Head extra dari child view -->
    <?= $this->renderSection('head') ?>
</head>

<body class="h-full">
    <!-- Elements (untuk <el-disclosure>, <el-dropdown>, dll) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt" class="size-8" />
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <!-- Current: "bg-gray-900 text-white" -->
                            <a href="<?= base_url('dashboard'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dashboard</a>
                            <a href="<?= base_url('/classes'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class</a>
                            <a href="<?= base_url('/class-sessions'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class Session</a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Calendar</a>
                            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reports</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <el-disclosure id="mobile-menu" hidden class="block md:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                <a href="<?= base_url('dashboard'); ?>" aria-current="page" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">Dashboard</a>
                <a href="<?= base_url('/classes'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class</a>
                <a href="<?= base_url('/class-sessions'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class Session</a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Calendar</a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reports</a>
            </div>
        </el-disclosure>
    </nav>
    <!-- Konten halaman -->
    <?= $this->renderSection('content') ?>

    <!-- Script extra dari child view -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>