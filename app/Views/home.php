<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Home — DianaArt<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<!-- Tombol mobile -->
<div class="flex lg:hidden">
    <button type="button" command="show-modal" commandfor="mobile-menu"
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
    <a href="<?= base_url('/login') ?>" class="text-sm font-semibold text-gray-900 hover:text-pink-500">Log in</a>
</nav>

<!-- Dialog mobile menu -->
<el-dialog>
    <dialog id="mobile-menu" class="backdrop:bg-black/30 lg:hidden">
        <div tabindex="0" class="fixed inset-0 focus:outline-none">
            <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="<?= base_url('/') ?>" class="-m-1.5 p-1.5 flex items-center">
                        <img src="<?= base_url('img/Dianaart.png'); ?>" alt="DianaArt Logo" class="h-8 w-8 mr-2">
                        <span class="text-pink-500 font-bold">Diana</span>Art
                    </a>
                    <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-gray-700">
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
                            <a href="<?= base_url('/login') ?>" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold text-gray-900 hover:bg-gray-50">Log in</a>
                        </div>
                    </div>
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Hero Section -->
<div class="relative overflow-hidden bg-white">
    <div class="pt-16 pb-80 sm:pt-24 sm:pb-40 lg:pt-40 lg:pb-48">
        <div class="relative mx-auto max-w-7xl px-4 sm:static sm:px-6 lg:px-8">
            <div class="sm:max-w-lg">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Summer styles are finally here</h1>
                <p class="mt-4 text-xl text-gray-500">This year, our new summer collection will shelter you from the harsh elements of a world that doesn't care if you live or die.</p>
            </div>
            <div>
                <div class="mt-10">
                    <!-- Decorative image grid -->
                    <div aria-hidden="true" class="pointer-events-none lg:absolute lg:inset-y-0 lg:mx-auto lg:w-full lg:max-w-7xl">
                        <div class="absolute transform sm:top-0 sm:left-1/2 sm:translate-x-8 lg:top-1/2 lg:left-1/2 lg:translate-x-8 lg:-translate-y-1/2">
                            <div class="flex items-center space-x-6 lg:space-x-8">
                                <div class="grid shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
                                    <div class="h-64 w-44 overflow-hidden rounded-lg sm:opacity-0 lg:opacity-100">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-01.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-02.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                </div>
                                <div class="grid shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-03.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-04.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-05.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                </div>
                                <div class="grid shrink-0 grid-cols-1 gap-y-6 lg:gap-y-8">
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-06.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                    <div class="h-64 w-44 overflow-hidden rounded-lg">
                                        <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-03-hero-image-tile-07.jpg" alt="" class="size-full object-cover" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('/shop/class-sessions') ?>" class="inline-block rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-center font-medium text-white hover:bg-indigo-700">Shop Collection</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>