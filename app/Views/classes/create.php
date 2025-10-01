<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Create Class<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900">Create Class</h1>

    <?php $errors = session('errors') ?? []; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('classes/store'); ?>" method="post" enctype="multipart/form-data" class="space-y-5 bg-white p-6 rounded-lg border">
        <?= csrf_field(); ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" value="<?= old('title') ?>" class="mt-1 w-full rounded border-gray-300" required>
            <?php if (!empty($errors['title'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['title']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <input type="text" name="description" value="<?= old('description') ?>" class="mt-1 w-full rounded border-gray-300">
            <?php if (!empty($errors['description'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['description']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input type="number" name="price" step="0.01" value="<?= old('price', '0.00') ?>" class="mt-1 w-full rounded border-gray-300">
            <?php if (!empty($errors['price'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['price']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded border-gray-300">
            <?php if (!empty($errors['image'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['image']) ?></p><?php endif; ?>
        </div>

        <div class="flex items-center gap-2">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="h-4 w-4 rounded border-gray-300" checked>
            <label for="is_active" class="text-sm text-gray-700">Active</label>
        </div>

        <div class="pt-2">
            <a href="<?= base_url('classes'); ?>" class="rounded border px-4 py-2 text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">Save</button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>