<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Home<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/login" class="text-gray-700 hover:text-pink-600">Login</a>
    <a href="/register" class="text-gray-700 hover:text-pink-600">Register</a>
</nav>
<?= $this->endSection(); ?>

<!-- Konten utama -->
<?= $this->section('content'); ?>
<section class="relative bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
        <div class="flex flex-col-reverse lg:flex-row items-center gap-8 lg:gap-16">

            <!-- Text -->
            <div class="text-center lg:text-left lg:w-1/2">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900">
                    Selamat Datang di <span class="text-pink-500">Diana</span>Art
                </h1>
                <p class="mt-4 text-base sm:text-lg text-gray-500">
                    Temukan koleksi eksklusif seni dan kerajinan tangan dengan sentuhan keindahan dan makna.
                    Setiap karya dipersembahkan dengan kualitas terbaik untuk mempercantik ruang, melengkapi gaya hidup, dan menghadirkan kehangatan di setiap momen Anda.
                </p>
                <div class="mt-6">
                    <a href="/shop/categories"
                        class="inline-block rounded-md bg-pink-500 px-6 py-3 text-white font-medium hover:bg-pink-700 transition">
                        Lihat Koleksi Produk
                    </a>
                </div>
            </div>

            <!-- Foto -->
            <div class="lg:w-1/2 grid grid-cols-2 gap-4">
                <img src="<?= base_url('img/homesatu.jpg'); ?>"
                    alt="Produk DianaArt" class="rounded-lg object-cover w-full h-40 sm:h-56 lg:h-64">
                <img src="<?= base_url('img/homedua.jpg'); ?>"
                    alt="Produk DianaArt" class="rounded-lg object-cover w-full h-40 sm:h-56 lg:h-64">
                <img src="<?= base_url('img/hometiga.jpg'); ?>"
                    alt="Produk DianaArt" class="rounded-lg object-cover w-full h-40 sm:h-56 lg:h-64">
                <img src="<?= base_url('img/homeempat.jpg'); ?>"
                    alt="Produk DianaArt" class="rounded-lg object-cover w-full h-40 sm:h-56 lg:h-64">
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>