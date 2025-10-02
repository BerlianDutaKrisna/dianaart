<!doctype html>
<html lang="id" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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

    <?php
    // === Auth session (aman/null-safe) ===
    $userId   = session('user_id')   ?? null;
    $userName = session('user_name') ?? null;
    $userRole = session('user_role') ?? null;
    $loggedIn = (bool) session('isLoggedIn');
    $initials = $userName ? mb_strtoupper(mb_substr(trim($userName), 0, 1)) : 'U';
    ?>

    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Left: Brand + Menu desktop -->
                <div class="flex items-center gap-6">
                    <a href="<?= base_url('/'); ?>" class="shrink-0 flex items-center gap-2">
                        <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt" class="size-8" />
                        <span class="text-sm font-semibold text-white">DianaArt</span>
                    </a>

                    <div class="hidden md:block">
                        <div class="ml-6 flex items-baseline space-x-4">
                            <a href="<?= base_url('dashboard'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dashboard</a>
                            <a href="<?= base_url('classes'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class</a>
                            <a href="<?= base_url('class-sessions'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class Session</a>
                            <a href="<?= base_url('discounts'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Discount</a>
                            <a href="<?= base_url('reports'); ?>" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reports</a>
                        </div>
                    </div>
                </div>

                <!-- Right: User area -->
                <div class="flex items-center gap-3">
                    <!-- Mobile trigger -->
                    <button type="button"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                        el-disclosure-button="mobile-menu"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/10 hover:text-white md:hidden">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <?php if (!$loggedIn): ?>
                        <a href="<?= base_url('login'); ?>" class="hidden md:inline-block rounded-md bg-pink-600 px-3 py-2 text-sm font-medium text-white hover:bg-pink-500">
                            Log in
                        </a>
                    <?php else: ?>
                        <!-- Avatar + Nama (trigger dialog user) -->
                        <button type="button" command="show-modal" commandfor="user-menu"
                            class="hidden md:inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-sm font-semibold text-white hover:bg-white/20">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-pink-500 text-white">
                                <?= esc($initials) ?>
                            </span>
                            <span class="max-w-[10rem] truncate"><?= esc($userName) ?></span>
                            <svg class="h-4 w-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dialog user (tengah layar) -->
                        <dialog id="user-menu" class="backdrop:bg-black/30">
                            <div class="fixed inset-0 flex items-center justify-center p-4">
                                <div class="w-80 overflow-hidden rounded-lg border border-white/10 bg-white shadow-xl">
                                    <div class="p-4">
                                        <p class="truncate text-base font-medium text-gray-900"><?= esc($userName) ?></p>
                                        <?php if (!empty($userRole)): ?>
                                            <p class="text-sm text-gray-500"><?= esc(ucfirst($userRole)) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="border-t border-gray-100"></div>
                                    <div class="py-2">
                                        <?php if ($userRole === 'admin'): ?>
                                            <a href="<?= base_url('dashboard'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                                        <?php endif; ?>

                                        <?php if ($userId): ?>
                                            <a href="<?= base_url('user/edit/' . $userId); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                        <?php endif; ?>

                                        <form action="<?= base_url('logout'); ?>" method="post">
                                            <?= csrf_field(); ?>
                                            <button class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <el-disclosure id="mobile-menu" hidden class="md:hidden">
            <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                <a href="<?= base_url('dashboard'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dashboard</a>
                <a href="<?= base_url('classes'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class</a>
                <a href="<?= base_url('class-sessions'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Class Session</a>
                <a href="<?= base_url('discounts'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Discount</a>
                <a href="<?= base_url('reports'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reports</a>

                <?php if (!$loggedIn): ?>
                    <a href="<?= base_url('login'); ?>" class="mt-2 block rounded-md bg-pink-600 px-3 py-2 text-base font-medium text-white hover:bg-pink-500">Log in</a>
                <?php else: ?>
                    <div class="mt-3 border-t border-white/10 pt-3">
                        <?php if ($userRole === 'admin'): ?>
                            <a href="<?= base_url('dashboard'); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Dashboard</a>
                        <?php endif; ?>
                        <?php if ($userId): ?>
                            <a href="<?= base_url('user/edit/' . $userId); ?>" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Profile</a>
                        <?php endif; ?>
                        <form action="<?= base_url('logout'); ?>" method="post" class="mt-2">
                            <?= csrf_field(); ?>
                            <button class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-red-300 hover:bg-white/5 hover:text-red-200">Logout</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </el-disclosure>
    </nav>

    <!-- Konten halaman -->
    <?= $this->renderSection('content') ?>

    <!-- Script extra dari child view -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>