<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Edit Product<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

    <form action="/products/update/<?= $product['id'] ?>" method="post" enctype="multipart/form-data" class="space-y-4">

        <div>
            <label class="block font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded p-2">
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= esc($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" value="<?= old('name', $product['name']) ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Flower Type</label>
            <select name="flower_type" class="w-full border rounded p-2">
                <?php $types = ['Mawar', 'Tulip', 'Lily', 'Anggrek', 'Daisy']; ?>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t ?>" <?= $t == $product['flower_type'] ? 'selected' : '' ?>><?= $t ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Flower Color</label>
            <select name="flower_color" class="w-full border rounded p-2">
                <?php $colors = ['Merah', 'Pink', 'Biru', 'Putih', 'Kuning', 'Ungu']; ?>
                <?php foreach ($colors as $c): ?>
                    <option value="<?= $c ?>" <?= $c == $product['flower_color'] ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded p-2"><?= old('description', $product['description']) ?></textarea>
        </div>

        <div>
            <label class="block font-semibold">Price</label>
            <input type="number" step="0.01" name="price" value="<?= old('price', $product['price']) ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Stock</label>
            <input type="number" name="stock" value="<?= old('stock', $product['stock']) ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Image</label>
            <?php if ($product['image']): ?>
                <img src="<?= base_url('uploads/products/' . $product['image']) ?>" class="h-24 w-24 object-cover rounded mb-2">
            <?php endif; ?>
            <input type="file" name="image" class="w-full border rounded p-2">
        </div>

        <div>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded">Update</button>
            <a href="/products" class="ml-2 text-gray-600">Cancel</a>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>