<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Edit Kategori<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/" class="text-gray-700 hover:text-pink-600">Beranda</a>
    <a href="/category" class="text-gray-700 hover:text-pink-600 font-bold">Kategori</a>
</nav>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-2xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Edit Kategori</h2>

    <form action="/category/update/<?= $category['id']; ?>" method="post" class="space-y-4">
        <?= csrf_field(); ?>

        <div>
            <label for="name" class="block mb-1 font-medium">Nama Kategori</label>
            <input type="text" name="name" id="name" value="<?= old('name', $category['name']); ?>"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
            <?php if (isset($validation) && $validation->hasError('name')): ?>
                <div class="text-red-600 mt-1"><?= $validation->getError('name'); ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Update</button>
    </form>
</div>
<?= $this->endSection(); ?>