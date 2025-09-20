<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Shop<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<!-- kosong, tidak ada navbar -->
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Categories</h2>

            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:space-y-0 lg:gap-x-6">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <div class="group relative">
                            <?php if ($category['image'] && file_exists(FCPATH . 'uploads/categories/' . $category['image'])): ?>
                                <a href="<?= base_url('/shop/products') ?>">
                                    <img src="<?= base_url('uploads/categories/' . $category['image']) ?>"
                                        alt="<?= esc($category['name']) ?>"
                                        class="w-full h-64 rounded-lg bg-white object-cover object-center group-hover:opacity-75" />
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('/shop/products') ?>">
                                    <img src="https://via.placeholder.com/400x300?text=No+Image"
                                        alt="<?= esc($category['name']) ?>"
                                        class="w-full h-64 rounded-lg bg-white object-cover object-center group-hover:opacity-75" />
                                </a>
                            <?php endif; ?>

                            <h3 class="mt-6 text-sm text-gray-500">
                                <a href="<?= base_url('/shop/products') ?>">
                                    <span class="absolute inset-0"></span>
                                    <?= esc($category['name']) ?>
                                </a>
                            </h3>
                            <p class="text-base font-semibold text-gray-900">
                                <?= esc($category['description']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center col-span-3">
                        <p class="text-gray-500 mb-4">Belum ada kategori.</p>
                        <a href="<?= base_url('/shop/products') ?>"
                            class="inline-block rounded-md bg-pink-500 px-6 py-3 text-white font-medium hover:bg-pink-700 transition">
                            Lihat Produk
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>