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
    <!-- Elements (untuk <el-dialog>, dll) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <?php
    // === Auth session (null-safe) ===
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
                    <!-- Mobile trigger → buka el-dialog #mobile-menu -->
                    <button type="button"
                        command="show-modal"
                        commandfor="mobile-menu"
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
                        <button type="button"
                            command="show-modal"
                            commandfor="user-menu"
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
                                    <!-- Header: nama + tombol close -->
                                    <div class="flex items-center justify-between p-4">
                                        <div>
                                            <p class="truncate text-base font-medium text-gray-900"><?= esc($userName) ?></p>
                                            <?php if (!empty($userRole)): ?>
                                                <p class="text-sm text-gray-500"><?= esc(ucfirst($userRole)) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button"
                                            command="close"
                                            commandfor="user-menu"
                                            class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                            aria-label="Close">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
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

        <!-- Dialog mobile menu (slide panel kanan) -->
        <el-dialog>
            <dialog id="mobile-menu" class="backdrop:bg-black/30 lg:hidden">
                <div tabindex="0" class="fixed inset-0 focus:outline-none">
                    <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                        <div class="flex items-center justify-between">
                            <a href="<?= base_url('/') ?>" class="-m-1.5 p-1.5 flex items-center">
                                <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="mr-2 h-8 w-8">
                                <span class="font-bold text-pink-500">Diana</span>Art
                            </a>
                            <button type="button"
                                command="close"
                                commandfor="mobile-menu"
                                class="-m-2.5 rounded-md p-2.5 text-gray-700">
                                <span class="sr-only">Close menu</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-6 flow-root">
                            <div class="-my-6 divide-y divide-gray-500/10">
                                <div class="space-y-2 py-6">
                                    <a href="<?= base_url('dashboard'); ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                                    <a href="<?= base_url('classes'); ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Class</a>
                                    <a href="<?= base_url('class-sessions'); ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Class Session</a>
                                    <a href="<?= base_url('discounts'); ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Discount</a>
                                    <a href="<?= base_url('reports'); ?>" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Reports</a>
                                </div>

                                <div class="py-6">
                                    <?php if (!$loggedIn): ?>
                                        <a href="<?= base_url('login') ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
                                    <?php else: ?>
                                        <?php if ($userRole === 'admin'): ?>
                                            <a href="<?= base_url('dashboard') ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                                        <?php endif; ?>

                                        <?php if (!empty($userId)): ?>
                                            <!-- sesuai router group: /user/edit/(:num) -->
                                            <a href="<?= base_url('user/edit/' . $userId) ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Profile</a>
                                        <?php endif; ?>

                                        <form action="<?= base_url('logout'); ?>" method="post" class="mt-2">
                                            <?= csrf_field(); ?>
                                            <button class="-mx-3 block w-full rounded-lg px-3 py-2.5 text-left text-base font-semibold text-red-600 hover:bg-red-50">Logout</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </el-dialog-panel>
                </div>
            </dialog>
        </el-dialog>
    </nav>

    <!-- Konten halaman -->
    <?= $this->renderSection('content') ?>

    <!-- Script extra dari child view -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>