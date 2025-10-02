<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Edit Discount<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Edit Discount: <span class="font-mono"><?= esc($discount['code']) ?></span></h1>
        <a href="<?= base_url('discounts'); ?>" class="rounded bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">← Back</a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <form method="post" action="<?= base_url('discounts/update/' . $discount['id']); ?>" class="space-y-4">
            <?= $this->include('discounts/_form') ?>
            <div class="flex items-center gap-3 pt-2">
                <button class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">Update</button>
                <a href="<?= base_url('discounts'); ?>" class="rounded px-4 py-2 text-gray-600 hover:bg-gray-100">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>