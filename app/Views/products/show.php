<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?><?= esc($product['name']) ?><?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">

        <!-- Produk Detail -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <div class="flex flex-col items-center">
                <img src="<?= base_url('uploads/products/' . $product['image']) ?>"
                    alt="<?= esc($product['name']) ?>"
                    class="aspect-square w-full rounded-md bg-gray-200 object-cover lg:aspect-auto lg:h-96" />
            </div>

            <div class="mt-6 lg:mt-0">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900"><?= esc($product['name']) ?></h1>
                <p class="mt-2 text-lg text-gray-600"><?= esc($product['flower_type']) ?> - <?= esc($product['flower_color']) ?></p>
                <p class="mt-4 text-2xl font-semibold text-pink-600">Rp <?= number_format($product['price'], 2, ',', '.') ?></p>
                <p class="mt-6 text-gray-700"><?= esc($product['description']) ?></p>

                <p class="mt-4 text-sm text-gray-500">Stock: <?= esc($product['stock']) ?></p>

                <div class="mt-6">
                    <a href="#" class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600">Add to Cart</a>
                </div>
            </div>
        </div>

        <!-- Produk terkait -->
        <h2 class="text-2xl font-bold tracking-tight text-gray-900 mt-16">Related Products</h2>
        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <?php if (!empty($relatedProducts)): ?>
                <?php foreach ($relatedProducts as $item): ?>
                    <div class="group relative">
                        <img src="<?= base_url('uploads/products/' . $item['image']) ?>"
                            alt="<?= esc($item['name']) ?>"
                            class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80" />
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="<?= base_url('products/show/' . $item['id']) ?>">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        <?= esc($item['name']) ?>
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500"><?= esc($item['flower_type']) ?> - <?= esc($item['flower_color']) ?></p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp <?= number_format($item['price'], 2, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">No related products found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>