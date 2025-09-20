<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Dashboard<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- User -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800">Kelola User</h2>
                <p class="mt-2 text-gray-600 text-sm">
                    Tambah, edit, dan hapus data user.
                </p>
                <a href="<?= base_url('user'); ?>"
                    class="mt-4 inline-block px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Lihat User
                </a>
            </div>

            <!-- Category -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800">Kelola Kategori</h2>
                <p class="mt-2 text-gray-600 text-sm">
                    Tambah, edit, dan hapus kategori produk.
                </p>
                <a href="<?= base_url('category'); ?>"
                    class="mt-4 inline-block px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Lihat Kategori
                </a>
            </div>

            <!-- Produk -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800">Kelola Produk</h2>
                <p class="mt-2 text-gray-600 text-sm">
                    Tambah, edit, dan hapus produk.
                </p>
                <a href="<?= base_url('product'); ?>"
                    class="mt-4 inline-block px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600">
                    Lihat Produk
                </a>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>