<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Detail Class<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <a href="<?= base_url('classes'); ?>" class="mb-4 inline-block text-sm text-indigo-600 hover:underline">&larr; Back</a>

    <div class="overflow-hidden rounded-lg border bg-white">
        <div class="p-6">
            <div class="flex gap-6">
                <div>
                    <?php if (!empty($class['image'])): ?>
                        <img src="<?= base_url('writable/uploads/classes/' . $class['image']); ?>" alt="" class="h-40 w-40 rounded object-cover">
                    <?php else: ?>
                        <div class="flex h-40 w-40 items-center justify-center rounded bg-gray-100 text-gray-400">No image</div>
                    <?php endif; ?>
                </div>

                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-gray-900"><?= esc($class['title']); ?></h2>
                    <p class="mt-2 text-gray-600"><?= esc($class['description']); ?></p>
                    <p class="mt-4 text-lg font-medium text-gray-900">Rp <?= number_format((float)$class['price'], 2, ',', '.') ?></p>
                    <p class="mt-1 text-sm">
                        Status:
                        <?php if ((int)$class['is_active'] === 1): ?>
                            <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                        <?php else: ?>
                            <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>