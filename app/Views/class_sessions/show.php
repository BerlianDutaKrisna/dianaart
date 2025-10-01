<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Detail Class Session<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <a href="<?= base_url('class-sessions'); ?>" class="mb-4 inline-block text-sm text-indigo-600 hover:underline">&larr; Back</a>

    <div class="overflow-hidden rounded-lg border bg-white">
        <div class="p-6 space-y-2">
            <h2 class="text-2xl font-semibold text-gray-900"><?= esc($session['name']); ?></h2>
            <p class="text-gray-600"><?= esc($session['description']); ?></p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="text-sm text-gray-500">Class</div>
                    <div class="font-medium text-gray-900"><?= esc($session['class_title'] ?? '—'); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Level</div>
                    <div class="font-medium text-gray-900"><?= esc($session['level'] ?? '—'); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Schedule</div>
                    <div class="font-medium text-gray-900">
                        <?= esc($session['schedule_date']); ?> • <?= esc($session['start_time']); ?> - <?= esc($session['end_time']); ?>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Capacity</div>
                    <div class="font-medium text-gray-900"><?= esc($session['capacity'] ?? '—'); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Location</div>
                    <div class="font-medium text-gray-900"><?= esc($session['location'] ?? '—'); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-medium text-gray-900"><?= esc($session['status'] ?: 'Scheduled'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>