<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Proposal Terkirim<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Terima kasih!</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                <?= esc($message); ?>
            </div>
        <?php endif; ?>

        <div class="rounded border bg-white p-4 space-y-3">
            <?php if (!empty($proposal['image_url'])): ?>
                <img src="<?= esc($proposal['image_url']); ?>" alt="<?= esc($proposal['title'] ?? ''); ?>" class="w-full h-48 object-cover rounded">
            <?php endif; ?>

            <div>
                <div class="text-sm text-gray-500">Judul</div>
                <div class="font-medium text-gray-900"><?= esc($proposal['title'] ?? ''); ?></div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Deskripsi</div>
                <div class="font-medium text-gray-900"><?= esc($proposal['description'] ?? ''); ?></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-gray-500">Tanggal</div>
                    <div class="font-medium text-gray-900"><?= esc($proposal['date_fmt'] ?? ($proposal['schedule_date'] ?? '')); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Lokasi</div>
                    <div class="font-medium text-gray-900"><?= esc($proposal['location'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Waktu</div>
                    <div class="font-medium text-gray-900">
                        <?= esc($proposal['time_fmt'] ?? (trim(($proposal['start_time'] ?? '') . (isset($proposal['end_time']) ? '–' . $proposal['end_time'] : '')))); ?>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Harga Perkiraan</div>
                    <div class="font-medium text-gray-900">Rp <?= number_format((float)($proposal['price'] ?? 0), 0, ',', '.'); ?></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-medium text-gray-900"><?= esc($proposal['status'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Diajukan Pada</div>
                    <div class="font-medium text-gray-900"><?= esc($proposal['created_at'] ?? ''); ?></div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <a href="<?= base_url('shop/class-sessions'); ?>" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Kembali ke Collections</a>
            <a href="<?= base_url('proposals/create'); ?>" class="text-sm text-gray-600 hover:underline">Buat lagi</a>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>