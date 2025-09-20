<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Shop<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<!-- kosong, tidak ada navbar -->
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Our Products</h2>

        <!-- Filter Form -->
        <form action="<?= site_url('shop/products') ?>" method="get"
            class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6 bg-gray-50 p-4 rounded shadow">

            <div>
                <label class="block font-semibold">Flower Type</label>
                <select name="flower_type" class="w-full border rounded p-2">
                    <option value="">-- Semua --</option>
                    <option value="Mawar" <?= (request()->getGet('flower_type') == 'Mawar') ? 'selected' : '' ?>>Mawar</option>
                    <option value="Tulip" <?= (request()->getGet('flower_type') == 'Tulip') ? 'selected' : '' ?>>Tulip</option>
                    <option value="Lily" <?= (request()->getGet('flower_type') == 'Lily') ? 'selected' : '' ?>>Lily</option>
                    <option value="Anggrek" <?= (request()->getGet('flower_type') == 'Anggrek') ? 'selected' : '' ?>>Anggrek</option>
                    <option value="Daisy" <?= (request()->getGet('flower_type') == 'Daisy') ? 'selected' : '' ?>>Daisy</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold">Flower Color</label>
                <select name="flower_color" class="w-full border rounded p-2">
                    <option value="">-- Semua --</option>
                    <option value="Merah" <?= (request()->getGet('flower_color') == 'Merah') ? 'selected' : '' ?>>Merah</option>
                    <option value="Pink" <?= (request()->getGet('flower_color') == 'Pink') ? 'selected' : '' ?>>Pink</option>
                    <option value="Biru" <?= (request()->getGet('flower_color') == 'Biru') ? 'selected' : '' ?>>Biru</option>
                    <option value="Putih" <?= (request()->getGet('flower_color') == 'Putih') ? 'selected' : '' ?>>Putih</option>
                    <option value="Kuning" <?= (request()->getGet('flower_color') == 'Kuning') ? 'selected' : '' ?>>Kuning</option>
                    <option value="Ungu" <?= (request()->getGet('flower_color') == 'Ungu') ? 'selected' : '' ?>>Ungu</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold">Harga Minimum</label>
                <input type="number" name="min_price" value="<?= esc(request()->getGet('min_price')) ?>"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-semibold">Harga Maksimum</label>
                <input type="number" name="max_price" value="<?= esc(request()->getGet('max_price')) ?>"
                    class="w-full border rounded p-2">
            </div>

            <div class="col-span-1 sm:col-span-4 flex gap-2">
                <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
                    Filter
                </button>
                <a href="<?= site_url('shop/products') ?>"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Reset
                </a>
            </div>
        </form>

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