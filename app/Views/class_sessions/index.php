<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Class Sessions<?= $this->endSection(); ?>

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
        <h1 class="text-2xl font-bold text-stone-900">Class Sessions</h1>
        <a href="<?= base_url('class-sessions/create'); ?>" class="rounded bg-stone-600 px-4 py-2 text-white hover:bg-stone-500">+ Create</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-stone-200 bg-white">
        <table class="min-w-full divide-y divide-stone-200">
            <thead class="bg-stone-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-stone-500">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Class</th>
                    <th class="px-4 py-3">Name Session</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Time</th>
                    <th class="px-4 py-3">Capacity</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                <?php if (!empty($class_sessions)): ?>
                    <?php foreach ($class_sessions as $s): ?>
                        <tr class="text-sm text-stone-700">
                            <td class="px-4 py-3"><?= $s['id']; ?></td>
                            <td class="px-4 py-3"><?= esc($s['class_title'] ?? '—'); ?></td>
                            <td class="px-4 py-3">
                                <a href="<?= base_url('class-sessions/show/' . $s['id']); ?>" class="font-medium text-stone-600 hover:underline">
                                    <?= esc($s['name']); ?>
                                </a>
                            </td>
                            <td class="px-4 py-3"><?= esc($s['schedule_date']); ?></td>
                            <td class="px-4 py-3"><?= esc($s['start_time']); ?> - <?= esc($s['end_time']); ?></td>
                            <td class="px-4 py-3"><?= esc($s['capacity'] ?? '—'); ?></td>
                            <td class="px-4 py-3">
                                <?php if (strtolower((string)$s['status']) === 'cancelled'): ?>
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Cancelled</span>
                                <?php elseif (strtolower((string)$s['status']) === 'completed'): ?>
                                    <span class="rounded bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">Completed</span>
                                <?php else: ?>
                                    <span class="rounded bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700"><?= esc($s['status'] ?: 'Scheduled'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="<?= base_url('class-sessions/edit/' . $s['id']); ?>" class="text-stone-600 hover:underline">Edit</a>
                                <form action="<?= base_url('class-sessions/delete/' . $s['id']); ?>" method="post" class="inline" onsubmit="return confirm('Hapus session ini?')">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-stone-500">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>