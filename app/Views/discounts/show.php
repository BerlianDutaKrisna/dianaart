<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Discount Detail<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Discount Detail</h1>
        <div class="flex items-center gap-2">
            <a href="<?= base_url('discounts/edit/' . $discount['id']); ?>" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">Edit</a>
            <form method="post" action="<?= base_url('discounts/delete/' . $discount['id']); ?>" onsubmit="return confirm('Hapus data ini?')" class="inline">
                <?= csrf_field(); ?>
                <button class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-500">Delete</button>
            </form>
            <a href="<?= base_url('discounts'); ?>" class="rounded bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">Back</a>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <dl class="divide-y divide-gray-200">
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="col-span-2 text-sm text-gray-900"><?= esc($discount['id']) ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Code</dt>
                <dd class="col-span-2 text-sm text-gray-900 font-mono"><?= esc($discount['code']) ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Type</dt>
                <dd class="col-span-2 text-sm text-gray-900"><?= esc($discount['type']) ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Value</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    <?php if ($discount['type'] === 'percentage'): ?>
                        <?= number_format((float)$discount['value'], 2) ?>%
                    <?php else: ?>
                        Rp <?= number_format((float)$discount['value'], 2, ',', '.') ?>
                    <?php endif; ?>
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Class</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    <?php if (!empty($class)): ?>
                        <?= esc($class['name'] ?? ('Class #' . $class['id'])) ?> (ID: <?= esc($class['id']) ?>)
                    <?php else: ?>
                        — Berlaku semua kelas —
                    <?php endif; ?>
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Min Participants</dt>
                <dd class="col-span-2 text-sm text-gray-900"><?= esc($discount['min_participants']) ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Max Usage</dt>
                <dd class="col-span-2 text-sm text-gray-900"><?= esc($discount['max_usage'] ?? '∞') ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Used Count</dt>
                <dd class="col-span-2 text-sm text-gray-900"><?= esc($discount['usage_count']) ?></dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Period</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    <div>Start: <?= esc($discount['starts_at'] ?: '—') ?></div>
                    <div>End: <?= esc($discount['ends_at'] ?: '—') ?></div>
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Active</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    <?php if ((int)$discount['is_active'] === 1): ?>
                        <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Active</span>
                    <?php else: ?>
                        <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Inactive</span>
                    <?php endif; ?>
                </dd>
            </div>
            <div class="grid grid-cols-3 gap-4 px-6 py-4">
                <dt class="text-sm font-medium text-gray-500">Created / Updated</dt>
                <dd class="col-span-2 text-sm text-gray-900">
                    <?= esc($discount['created_at'] ?: '—') ?> / <?= esc($discount['updated_at'] ?: '—') ?>
                </dd>
            </div>
        </dl>
    </div>
</div>
<?= $this->endSection(); ?>