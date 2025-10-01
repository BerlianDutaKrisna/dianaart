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
    <a href="<?= base_url('/login') ?>" class="ml-6 text-sm font-semibold text-gray-900 hover:text-pink-500">Log in <span aria-hidden="true">&rarr;</span></a>
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
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Customers also purchased</h2>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <div class="group relative">
                <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-01-related-product-01.jpg" alt="Front of men's Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Basic Tee
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Black</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$35</p>
                </div>
            </div>

            <div class="group relative">
                <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-01-related-product-02.jpg" alt="Front of men's Basic Tee in white." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Basic Tee
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Aspen White</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$35</p>
                </div>
            </div>

            <div class="group relative">
                <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-01-related-product-03.jpg" alt="Front of men's Basic Tee in dark gray." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Basic Tee
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Charcoal</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$35</p>
                </div>
            </div>

            <div class="group relative">
                <img src="https://tailwindcss.com/plus-assets/img/ecommerce-images/product-page-01-related-product-04.jpg" alt="Front of men's Artwork Tee in peach with white and brown dots forming an isometric cube." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                <div class="mt-4 flex justify-between">
                    <div>
                        <h3 class="text-sm text-gray-700">
                            <a href="#">
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                Artwork Tee
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Iso Dots</p>
                    </div>
                    <p class="text-sm font-medium text-gray-900">$35</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>