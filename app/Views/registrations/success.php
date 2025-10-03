<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Registration Success<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Registration Successful</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                <?= esc($message); ?>
            </div>
        <?php endif; ?>

        <div class="rounded border bg-white p-4 space-y-3">
            <div>
                <div class="text-sm text-gray-500">Nama</div>
                <div class="font-medium text-gray-900"><?= esc($user['name'] ?? ''); ?></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-gray-500">ID Registrasi</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['id'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['status'] ?? ''); ?></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-gray-500">Class</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['class_title'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Session</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['session_name'] ?? ''); ?></div>
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Schedule</div>
                <div class="font-medium text-gray-900">
                    <?= esc($reg['session_date'] ?? ''); ?>
                    • <?= esc(($reg['session_start'] ?? '') . (isset($reg['session_end']) ? '–' . $reg['session_end'] : '')); ?>
                    <?php if (!empty($reg['session_location'])): ?>
                        • <?= esc($reg['session_location']); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-gray-500">Quantity</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['quantity'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Unit Price</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['unit_price_fmt'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Subtotal</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['subtotal_fmt'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="font-medium text-gray-900"><?= esc($reg['final_total_fmt'] ?? ''); ?></div>
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Registered At</div>
                <div class="font-medium text-gray-900"><?= esc($reg['registered_at'] ?? ''); ?></div>
            </div>
        </div>

        <?php
        // Compose pesan WA yang mudah dibaca
        $userName   = $user['name'] ?? '';
        $regId      = $reg['id'] ?? '';
        $totalText  = $reg['final_total_fmt'] ?? '';
        $classTitle = $reg['class_title'] ?? '';
        $sessName   = $reg['session_name'] ?? '';
        $sched      = trim(($reg['session_date'] ?? '') . ' ' . ($reg['session_start'] ?? '') . (isset($reg['session_end']) ? '–' . $reg['session_end'] : ''));
        $waNum      = $wa_number_intl ?? ''; // contoh: 62865733771515

        $msg = "Hai, saya sudah registrasi.%0A"
            . "Nama: {$userName}%0A"
            . "ID Registrasi: {$regId}%0A"
            . "Kelas: {$classTitle}%0A"
            . "Sesi: {$sessName}%0A"
            . "Jadwal: {$sched}%0A"
            . "Total: {$totalText}%0A"
            . "Terima kasih.";
        $waUrl = $waNum !== '' ? "https://wa.me/{$waNum}?text={$msg}" : '';
        ?>

        <div class="mt-6 flex items-center gap-3">
            <a href="<?= base_url('class-sessions'); ?>" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                Back to Classes
            </a>

            <?php if ($waUrl !== ''): ?>
                <a href="<?= esc($waUrl); ?>" target="_blank" rel="noopener" class="rounded border px-4 py-2 text-gray-700 hover:bg-gray-50">
                    Kirim via WhatsApp
                </a>
            <?php endif; ?>
        </div>

        <p class="text-xs text-gray-400 mt-3">Tip: Ambil screenshot halaman ini sebagai bukti registrasi.</p>
    </div>
</div>
<?= $this->endSection(); ?>