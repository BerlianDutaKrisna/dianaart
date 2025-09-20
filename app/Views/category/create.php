<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Create Categories<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/dashboard" class="text-gray-700 hover:text-pink-600">Dashboard</a>
    <a href="/category" class="text-gray-700 hover:text-pink-600">Categories</a>
    <a href="/products" class="text-gray-700 hover:text-pink-600">Products</a>
    <a href="/users" class="text-gray-700 hover:text-pink-600">Users</a>
    <a href="/logout" class="text-gray-700 hover:text-pink-600">Logout</a>
</nav>
<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Tambah Kategori</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/category/store" method="post" enctype="multipart/form-data" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Name -->
        <div>
            <label for="name" class="block font-medium">Nama Kategori</label>
            <input type="text" name="name" id="name" value="<?= old('name') ?>"
                class="border rounded w-full p-2">
            <?php if (isset($validation) && $validation->hasError('name')): ?>
                <div class="text-red-500 text-sm mt-1"><?= $validation->getError('name') ?></div>
            <?php endif; ?>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block font-medium">Deskripsi</label>
            <textarea name="description" id="description" rows="4"
                class="border rounded w-full p-2"><?= old('description') ?></textarea>
            <?php if (isset($validation) && $validation->hasError('description')): ?>
                <div class="text-red-500 text-sm mt-1"><?= $validation->getError('description') ?></div>
            <?php endif; ?>
        </div>

        <!-- Image -->
        <div>
            <label for="image" class="block font-medium">Image</label>
            <input type="file" name="image" id="image" class="border rounded w-full p-2">
            <?php if (isset($validation) && $validation->hasError('image')): ?>
                <div class="text-red-500 text-sm mt-1"><?= $validation->getError('image') ?></div>
            <?php endif; ?>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-700 transition">
            Simpan
        </button>
    </form>
</div>
<?= $this->endSection() ?>