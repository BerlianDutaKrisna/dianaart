<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Registrations<?= $this->endSection(); ?>

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
        <h1 class="text-2xl font-bold text-gray-900">Registrations</h1>
        <!-- biasanya registrasi dibuat dari halaman sesi/kelas, jadi tidak ada tombol create -->
        <div></div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Session</th>
                    <th class="px-4 py-3">Qty</th>
                    <th class="px-4 py-3">Unit Price</th>
                    <th class="px-4 py-3">Final Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Registered At</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($registrations)): ?>
                    <?php foreach ($registrations as $r): ?>
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3"><?= (int)$r['id'] ?></td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?= esc($r['user_name'] ?? '—') ?></div>
                                <div class="text-xs text-gray-500"><?= esc($r['user_email'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium"><?= esc($r['session_name'] ?? '—') ?></div>
                                <div class="text-xs text-gray-500"><?= esc($r['class_title'] ?? '') ?></div>
                            </td>
                            <td class="px-4 py-3"><?= (int)$r['quantity'] ?></td>
                            <td class="px-4 py-3">Rp <?= number_format((float)($r['unit_price'] ?? 0), 2, ',', '.') ?></td>
                            <td class="px-4 py-3 font-semibold">Rp <?= number_format((float)($r['final_total'] ?? 0), 2, ',', '.') ?></td>
                            <td class="px-4 py-3">
                                <?php $status = trim($r['status'] ?? ''); ?>
                                <?php if (strtolower($status) === 'registered'): ?>
                                    <span class="rounded bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700">Registered</span>
                                <?php elseif (strtolower($status) === 'paid'): ?>
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs font-medium text-green-700">Paid</span>
                                <?php elseif (strtolower($status) === 'cancelled'): ?>
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs font-medium text-red-700">Cancelled</span>
                                <?php else: ?>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700"><?= esc($status ?: '—') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div><?= esc(date('d M Y H:i', strtotime($r['registered_at'] ?? 'now'))) ?></div>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="<?= base_url('register/success/' . $r['id']); ?>" class="text-indigo-600 hover:underline">Detail</a>
                                <form action="<?= base_url('registrations/delete/' . $r['id']); ?>" method="post" class="inline" onsubmit="return confirm('Hapus registrasi ini?')">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>