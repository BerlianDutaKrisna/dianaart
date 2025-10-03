<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Register<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Registration</h1>

        <?php $errors = session('errors') ?? []; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <?= esc($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('register'); ?>" method="post" class="space-y-6">
            <?= csrf_field(); ?>

            <?php if (!empty($selectedSession)): ?>
                <input type="hidden" name="session_id" value="<?= esc($selectedSession['id'] ?? ''); ?>">
                <div class="rounded border bg-white p-4 space-y-2">
                    <div>
                        <div class="text-sm text-gray-500">Class</div>
                        <div class="font-medium text-gray-900"><?= esc($selectedSession['class_title'] ?? ''); ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Session</div>
                        <div class="font-medium text-gray-900"><?= esc($selectedSession['name'] ?? ''); ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Schedule</div>
                        <div class="font-medium text-gray-900">
                            <?= esc($selectedSession['schedule_date'] ?? ''); ?>
                            • <?= esc(($selectedSession['start_time'] ?? '') . (isset($selectedSession['end_time']) ? '–' . $selectedSession['end_time'] : '')); ?>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Unit Price</div>
                        <div class="font-medium text-gray-900">
                            <?= esc($price_fmt ?? ('Rp ' . number_format((float)($unit_price ?? 0), 0, ',', '.'))); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Choose Session</label>
                    <select name="session_id" class="mt-1 block w-full rounded border-gray-300">
                        <option value="">— Select —</option>
                        <?php foreach (($sessions ?? []) as $s): ?>
                            <?php
                            $optTitle = trim(($s['class_title'] ?? '') . ' — ' . ($s['name'] ?? ''));
                            $optSched = trim(($s['schedule_date'] ?? '') . ' • ' . ($s['start_time'] ?? '') . (isset($s['end_time']) ? '–' . $s['end_time'] : ''));
                            $optPrice = 'Rp ' . number_format((float)($s['class_price'] ?? 0), 0, ',', '.');
                            ?>
                            <option value="<?= esc($s['id'] ?? ''); ?>">
                                <?= esc($optTitle); ?> (<?= esc($optSched); ?>) — <?= esc($optPrice); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['session_id'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= esc($errors['session_id']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="number" min="1" name="quantity" value="<?= esc(old('quantity', $quantity ?? 1)); ?>" class="mt-1 block w-32 rounded border-gray-300" />
                <?php if (!empty($errors['quantity'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= esc($errors['quantity']); ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    Confirm Registration
                </button>
                <a href="<?= base_url('class-sessions'); ?>" class="text-sm text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>