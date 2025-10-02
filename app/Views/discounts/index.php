<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Discounts<?= $this->endSection(); ?>

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

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Discounts</h1>

        <div class="flex items-center gap-2">
            <form method="get" action="<?= base_url('discounts'); ?>" class="flex items-center gap-2">
                <input
                    type="text" name="q" value="<?= esc($q ?? '') ?>"
                    class="w-56 rounded border border-gray-300 px-3 py-2 text-sm text-gray-700 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                    placeholder="Cari kode/tipe..." />
                <button class="rounded bg-gray-100 px-3 py-2 text-sm text-gray-700 hover:bg-gray-200">Cari</button>
                <a href="<?= base_url('discounts'); ?>" class="rounded px-3 py-2 text-sm text-gray-500 hover:bg-gray-100">Reset</a>
            </form>

            <a href="<?= base_url('discounts/create'); ?>" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">+ Create</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Value</th>
                    <th class="px-4 py-3">Min</th>
                    <th class="px-4 py-3">Max Usage</th>
                    <th class="px-4 py-3">Used</th>
                    <th class="px-4 py-3">Period</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($discounts)): ?>
                    <?php foreach ($discounts as $d): ?>
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3"><?= $d['id'] ?></td>
                            <td class="px-4 py-3">
                                <a href="<?= base_url('discounts/show/' . $d['id']) ?>" class="font-medium text-indigo-600 hover:underline">
                                    <?= esc($d['code']) ?>
                                </a>
                            </td>
                            <td class="px-4 py-3"><?= esc($d['type']) ?></td>
                            <td class="px-4 py-3">
                                <?php if ($d['type'] === 'percentage'): ?>
                                    <?= number_format((float)$d['value'], 2) ?>%
                                <?php else: ?>
                                    Rp <?= number_format((float)$d['value'], 2, ',', '.') ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?= esc($d['min_participants']) ?></td>
                            <td class="px-4 py-3"><?= esc($d['max_usage'] ?? '∞') ?></td>
                            <td class="px-4 py-3"><?= esc($d['usage_count']) ?></td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-600">
                                    <div>Start: <?= esc($d['starts_at'] ?: '—') ?></div>
                                    <div>End: <?= esc($d['ends_at'] ?: '—') ?></div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ((int)$d['is_active'] === 1): ?>
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                                <?php else: ?>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="<?= base_url('discounts/show/' . $d['id']); ?>" class="text-sky-600 hover:underline">Show</a>
                                    <a href="<?= base_url('discounts/edit/' . $d['id']); ?>" class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="<?= base_url('discounts/delete/' . $d['id']); ?>" method="post" onsubmit="return confirm('Hapus data ini?')" class="inline">
                                        <?= csrf_field(); ?>
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>