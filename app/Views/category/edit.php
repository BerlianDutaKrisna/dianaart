<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Edit Categories<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/dashboard" class="text-gray-700 hover:text-pink-600">Dashboard</a>
    <a href="/category" class="text-gray-700 hover:text-pink-600">Categories</a>
    <a href="/products" class="text-gray-700 hover:text-pink-600">Products</a>
    <a href="/users" class="text-gray-700 hover:text-pink-600">Users</a>
    <a href="/logout" class="text-gray-700 hover:text-pink-600">Logout</a>
</nav>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Kategori</h2>

    <form action="/category/update/<?= $category['id']; ?>" method="post" enctype="multipart/form-data" class="space-y-4">
        <?= csrf_field(); ?>

        <!-- Name -->
        <div>
            <label for="name" class="block mb-1 font-medium">Nama Kategori</label>
            <input type="text" name="name" id="name" value="<?= old('name', $category['name']); ?>"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
            <?php if (isset($validation) && $validation->hasError('name')): ?>
                <div class="text-red-600 mt-1"><?= $validation->getError('name'); ?></div>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block mb-1 font-medium">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"><?= old('description', $category['description']); ?></textarea>
            <?php if (isset($validation) && $validation->hasError('description')): ?>
                <div class="text-red-600 mt-1"><?= $validation->getError('description'); ?></div>
            <?php endif; ?>
        </div>

        <!-- Image -->
        <div>
            <label for="image" class="block mb-1 font-medium">Gambar</label>
            <?php if (!empty($category['image'])): ?>
                <img src="<?= base_url('uploads/categories/' . $category['image']); ?>"
                    alt="Category Image"
                    class="h-24 w-24 object-cover rounded mb-2">
            <?php else: ?>
                <p class="text-gray-500 text-sm mb-2">Belum ada gambar</p>
            <?php endif; ?>
            <input type="file" name="image" id="image"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
            <?php if (isset($validation) && $validation->hasError('image')): ?>
                <div class="text-red-600 mt-1"><?= $validation->getError('image'); ?></div>
            <?php endif; ?>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Update</button>
            <a href="/category" class="ml-3 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>