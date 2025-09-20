<?= $this->extend('template/layout') ?>

<?= $this->section('title') ?>Tambah Kategori<?= $this->endSection() ?>

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
            <?php if ($validation->hasError('name')): ?>
                <div class="text-red-500"><?= $validation->getError('name') ?></div>
            <?php endif; ?>
        </div>

        <!-- Slug -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm"><?= old('description') ?></textarea>
        </div>

        <!-- Image -->
        <div>
            <label for="image" class="block font-medium">Image</label>
            <input type="file" name="image" id="image" class="border rounded w-full p-2">
            <?php if ($validation->hasError('image')): ?>
                <div class="text-red-500"><?= $validation->getError('image') ?></div>
            <?php endif; ?>
        </div>

        <button type="submit"
            class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-700 transition">
            Simpan
        </button>
    </form>
</div>
<?= $this->endSection() ?>