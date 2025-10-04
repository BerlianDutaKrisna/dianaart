<?= $this->extend('template/layout'); ?>
<?= $this->section('title') ?>Daftar Order Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto mt-8">
    <div class="bg-white shadow rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Order / Proposal Saya</h1>
        </div>

        <?php if (empty($orders)): ?>
            <p class="text-gray-600">Belum ada order atau proposal.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border text-center">#</th>
                            <th class="px-3 py-2 border">Tipe</th>
                            <th class="px-3 py-2 border">Kelas / Proposal</th>
                            <th class="px-3 py-2 border">Sesi</th>
                            <th class="px-3 py-2 border text-center">Tanggal</th>
                            <th class="px-3 py-2 border text-center">Waktu</th>
                            <th class="px-3 py-2 border">Lokasi</th>
                            <th class="px-3 py-2 border text-center">Status</th>
                            <th class="px-3 py-2 border text-center">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $i => $o): ?>
                            <?php
                            $isSession   = ($o['source_type'] ?? '') === 'session';
                            $tanggal     = $o['date'] ?? null;
                            $tanggalFmt  = $tanggal ? date('d M Y', strtotime($tanggal)) : '-';
                            $start       = $o['start_time'] ?? null;
                            $end         = $o['end_time'] ?? null;
                            $waktuFmt    = ($start && $end) ? date('H:i', strtotime($start)) . ' - ' . date('H:i', strtotime($end)) : '-';
                            $orderedAt   = $o['ordered_at'] ?? null;
                            $statusVal   = strtolower(trim($o['order_status'] ?? ''));
                            $statusClass = 'bg-gray-100 text-gray-700';
                            if (in_array($statusVal, ['paid', 'registered', 'success', 'complete', 'approved'])) {
                                $statusClass = 'bg-emerald-100 text-emerald-700';
                            } elseif (in_array($statusVal, ['unpaid', 'pending', 'waiting'])) {
                                $statusClass = 'bg-amber-100 text-amber-700';
                            } elseif (in_array($statusVal, ['cancelled', 'canceled', 'rejected', 'failed'])) {
                                $statusClass = 'bg-rose-100 text-rose-700';
                            }
                            ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-3 py-2 border text-center"><?= $i + 1 ?></td>

                                <td class="px-3 py-2 border text-center">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                        <?= $isSession ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' ?>">
                                        <?= $isSession ? 'Sesi Kelas' : 'Proposal' ?>
                                    </span>
                                </td>

                                <td class="px-3 py-2 border font-medium text-gray-800">
                                    <?= esc($o['class_title'] ?? '-') ?>
                                </td>

                                <td class="px-3 py-2 border text-gray-700">
                                    <?= esc($o['session_name'] ?? '-') ?>
                                </td>

                                <td class="px-3 py-2 border text-center">
                                    <?= esc($tanggalFmt) ?>
                                </td>

                                <td class="px-3 py-2 border text-center">
                                    <?= esc($waktuFmt) ?>
                                </td>

                                <td class="px-3 py-2 border">
                                    <?= esc($o['location'] ?? '-') ?>
                                </td>

                                <td class="px-3 py-2 border text-center">
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded <?= $statusClass ?>">
                                        <?= esc(ucfirst($o['order_status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td class="px-3 py-2 border text-center text-gray-600">
                                    <?= $orderedAt ? date('d M Y H:i', strtotime($orderedAt)) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>