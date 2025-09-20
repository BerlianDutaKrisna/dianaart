<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Shop<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<!-- kosong, tidak ada navbar -->
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Our Products</h2>

        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="group relative">
                        <?php if ($product['image'] && file_exists(FCPATH . 'uploads/products/' . $product['image'])): ?>
                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>"
                                alt="<?= esc($product['name']) ?>"
                                class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x300?text=No+Image"
                                alt="<?= esc($product['name']) ?>"
                                class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                        <?php endif; ?>

                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        <?= esc($product['name']) ?>
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    <?= esc($product['flower_type']) ?> - <?= esc($product['flower_color']) ?><br>
                                    <span class="italic"><?= esc($product['category_name']) ?></span>
                                </p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Belum ada produk.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>