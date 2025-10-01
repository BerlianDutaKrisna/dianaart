<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Classes<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Classes</h1>
        <a href="<?= base_url('classes/create'); ?>" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">+ Create</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($classes)): ?>
                    <?php foreach ($classes as $c): ?>
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3"><?= $c['id'] ?></td>
                            <td class="px-4 py-3">
                                <?php if ($c['image']): ?>
                                    <img src="<?= base_url('uploads/classes/' . $c['image']); ?>" alt="" class="h-12 w-12 rounded object-cover">
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <a href="<?= base_url('classes/show/' . $c['id']) ?>" class="font-medium text-indigo-600 hover:underline">
                                    <?= esc($c['title']) ?>
                                </a>
                                <div class="text-xs text-gray-500"><?= esc($c['description']) ?></div>
                            </td>
                            <td class="px-4 py-3">Rp <?= number_format((float)$c['price'], 2, ',', '.') ?></td>
                            <td class="px-4 py-3">
                                <?php if ((int)$c['is_active'] === 1): ?>
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                                <?php else: ?>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="<?= base_url('classes/edit/' . $c['id']); ?>" class="text-indigo-600 hover:underline">Edit</a>
                                <form action="<?= base_url('classes/delete/' . $c['id']); ?>" method="post" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>