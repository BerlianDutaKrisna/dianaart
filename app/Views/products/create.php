<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Create Product<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Add New Product</h1>

    <form action="/products/store" method="post" enctype="multipart/form-data" class="space-y-4">

        <div>
            <label class="block font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded p-2">
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Name</label>
            <input type="text" name="name" value="<?= old('name') ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Flower Type</label>
            <select name="flower_type" class="w-full border rounded p-2">
                <option value="Mawar">Mawar</option>
                <option value="Tulip">Tulip</option>
                <option value="Lily">Lily</option>
                <option value="Anggrek">Anggrek</option>
                <option value="Daisy">Daisy</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Flower Color</label>
            <select name="flower_color" class="w-full border rounded p-2">
                <option value="Merah">Merah</option>
                <option value="Pink">Pink</option>
                <option value="Biru">Biru</option>
                <option value="Putih">Putih</option>
                <option value="Kuning">Kuning</option>
                <option value="Ungu">Ungu</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded p-2"><?= old('description') ?></textarea>
        </div>

        <div>
            <label class="block font-semibold">Price</label>
            <input type="number" step="0.01" name="price" value="<?= old('price') ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Stock</label>
            <input type="number" name="stock" value="<?= old('stock') ?>" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold">Image</label>
            <input type="file" name="image" class="w-full border rounded p-2">
        </div>

        <div>
            <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded">Save</button>
            <a href="/products" class="ml-2 text-gray-600">Cancel</a>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>