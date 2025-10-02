<!doctype html>
<html lang="id">

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

    <title><?= $this->renderSection('title') ?: 'DianaArt' ?></title>
    <?= $this->renderSection('head') ?>
</head>

<body class="bg-white antialiased">
    <!-- Elements: untuk <el-dialog> & command="show-modal" -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <?php
    // data auth aman (null-safe)
    $userId   = session('user_id')   ?? null;
    $authName = session('user_name') ?? null;
    $authRole = session('user_role') ?? null;
    $isLogged = (bool) session('isLoggedIn');
    $initials = $authName ? mb_strtoupper(mb_substr(trim($authName), 0, 1)) : 'U';

    // kalau child view mendefinisikan section('navbar'), pakai itu
    $navbarCustom = $this->renderSection('navbar');
    ?>

    <!-- Header -->
    <header class="absolute inset-x-0 top-0 z-50">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <h1 class="flex items-center text-xl font-bold text-gray-900">
                <a href="<?= base_url('/') ?>" class="flex items-center transition hover:text-pink-500">
                    <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="mr-2 h-8 w-8">
                    <span class="text-pink-500">Diana</span>Art
                </a>
            </h1>

            <?php if ($navbarCustom !== ''): ?>
                <?= $navbarCustom ?>
            <?php else: ?>
                <!-- ===== Navbar Default (desktop + mobile) ===== -->

                <!-- Tombol mobile (hamburger) -->
                <div class="flex lg:hidden">
                    <button type="button"
                        command="show-modal"
                        commandfor="mobile-menu"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <!-- Menu desktop -->
                <nav class="hidden lg:flex lg:items-center lg:gap-x-8">
                    <a href="#" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Product</a>
                    <a href="#" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Features</a>
                    <a href="#" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Marketplace</a>
                    <a href="#" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Company</a>

                    <?php if (!$isLogged): ?>
                        <a href="<?= base_url('login') ?>" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Log in</a>
                    <?php else: ?>
                        <!-- Dropdown user (trigger dialog tengah layar) -->
                        <div class="relative">
                            <!-- Trigger dialog -->
                            <button type="button"
                                command="show-modal"
                                commandfor="user-menu"
                                class="flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-900 hover:bg-gray-200">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-pink-500 text-white">
                                    <?= esc($initials) ?>
                                </span>
                                <span class="hidden md:inline max-w-[10rem] truncate"><?= esc($authName) ?></span>
                                <svg class="ml-1 h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dialog user (tengah layar) -->
                            <dialog id="user-menu" class="backdrop:bg-black/30">
                                <div class="fixed inset-0 flex items-center justify-center p-4">
                                    <div class="w-80 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl">
                                        <!-- Header user + tombol close di sebelah nama -->
                                        <div class="flex items-center justify-between p-4">
                                            <div>
                                                <p class="truncate text-base font-medium text-gray-900"><?= esc($authName) ?></p>
                                                <?php if ($authRole): ?>
                                                    <p class="text-sm text-gray-500"><?= esc(ucfirst($authRole)) ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <button type="button" command="close" commandfor="user-menu"
                                                class="rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700"
                                                aria-label="Close">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="border-t border-gray-100"></div>

                                        <div class="py-2">
                                            <?php if ($authRole === 'admin'): ?>
                                                <a href="<?= base_url('dashboard'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                                            <?php endif; ?>

                                            <?php if ($userId): ?>
                                                <a href="<?= base_url('user/edit/' . $userId); ?>"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    Profile
                                                </a>
                                            <?php endif; ?>

                                            <form action="<?= base_url('logout'); ?>" method="post">
                                                <?= csrf_field(); ?>
                                                <button class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </dialog>
                        </div>
                    <?php endif; ?>
                </nav>

                <!-- Dialog mobile menu -->
                <el-dialog>
                    <dialog id="mobile-menu" class="backdrop:bg-black/30 lg:hidden">
                        <div tabindex="0" class="fixed inset-0 focus:outline-none">
                            <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                                <div class="flex items-center justify-between">
                                    <a href="<?= base_url('/') ?>" class="-m-1.5 p-1.5 flex items-center">
                                        <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="mr-2 h-8 w-8">
                                        <span class="font-bold text-pink-500">Diana</span>Art
                                    </a>
                                    <button type="button" command="close" commandfor="mobile-menu"
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
                                            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Product</a>
                                            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Features</a>
                                            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Marketplace</a>
                                            <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-gray-900 hover:bg-gray-50">Company</a>
                                        </div>

                                        <div class="py-6">
                                            <?php if (!$isLogged): ?>
                                                <a href="<?= base_url('login') ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
                                            <?php else: ?>
                                                <?php if ($authRole === 'admin'): ?>
                                                    <a href="<?= base_url('dashboard') ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Dashboard</a>
                                                <?php endif; ?>

                                                <?php if ($userId): ?>
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
                <!-- ===== /Navbar Default ===== -->
            <?php endif; ?>
        </div>
    </header>

    <!-- Konten utama -->
    <main class="py-8"><?= $this->renderSection('content'); ?></main>

    <footer class="border-t">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-8 text-sm text-gray-500 sm:px-6 lg:px-8">
            <p>© <?= date('Y') ?> DianaArt. All rights reserved.</p>
            <div class="space-x-4">
                <a href="#" class="hover:text-gray-700">Privacy</a>
                <a href="#" class="hover:text-gray-700">Terms</a>
            </div>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>

</html>