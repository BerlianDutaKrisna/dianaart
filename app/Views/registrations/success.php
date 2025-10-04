<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Registration Success<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-stone-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-stone-900 mb-4">Registration Successful</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                <?= esc($message); ?>
            </div>
        <?php endif; ?>

        <?php
        // helper untuk format jadwal
        if (!function_exists('formatScheduleFull')) {
            function formatScheduleFull(?string $date, ?string $start, ?string $end, ?string $location): string
            {
                $date     = $date ?: '';
                $start    = $start ?: '';
                $end      = $end ?: '';
                $location = $location ?: '';

                $tz = new DateTimeZone('Asia/Jakarta');

                $dtStart = null;
                $dtEnd = null;
                if ($date !== '') {
                    $dtStart = new DateTimeImmutable(trim($date . ' ' . ($start !== '' ? $start : '00:00')), $tz);
                    if ($end !== '') {
                        $dtEnd = new DateTimeImmutable(trim($date . ' ' . $end), $tz);
                    }
                }

                // format tanggal
                if ($dtStart) {
                    if (class_exists('IntlDateFormatter')) {
                        $fmtDate = new IntlDateFormatter(
                            'id_ID',
                            IntlDateFormatter::FULL,
                            IntlDateFormatter::NONE,
                            $tz->getName(),
                            IntlDateFormatter::GREGORIAN,
                            'EEEE, dd MMM yyyy'
                        );
                        $dayDate = $fmtDate->format($dtStart);
                    } else {
                        $dayDate = $dtStart->format('l, d M Y');
                    }
                } else {
                    $dayDate = $date;
                }

                // format waktu
                $timePart = '';
                if ($dtStart && $start !== '') {
                    if (class_exists('IntlDateFormatter')) {
                        $fmtTime = new IntlDateFormatter(
                            'id_ID',
                            IntlDateFormatter::NONE,
                            IntlDateFormatter::NONE,
                            $tz->getName(),
                            IntlDateFormatter::GREGORIAN,
                            'HH:mm'
                        );
                        $startStr = $fmtTime->format($dtStart);
                        $endStr   = $dtEnd ? $fmtTime->format($dtEnd) : '';
                    } else {
                        $startStr = $dtStart->format('H:i');
                        $endStr   = $dtEnd ? $dtEnd->format('H:i') : '';
                    }
                    $timePart = $endStr !== '' ? ($startStr . '–' . $endStr) : $startStr;
                } elseif ($start !== '') {
                    $timePart = $end !== '' ? ($start . '–' . $end) : $start;
                }

                // lokasi
                $where = $location !== '' ? (' @ ' . $location) : '';

                $pieces = [];
                if ($dayDate !== '')  $pieces[] = $dayDate;
                if ($timePart !== '') $pieces[] = $timePart;

                return !empty($pieces) ? (implode(' • ', $pieces) . $where) : ($where !== '' ? ltrim($where, ' ') : '');
            }
        }
        ?>

        <div class="rounded border bg-white p-4 space-y-3">
            <div>
                <div class="text-sm text-stone-500">Nama</div>
                <div class="font-medium text-stone-900"><?= esc($user['name'] ?? ''); ?></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-stone-500">ID Registrasi</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['id'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-stone-500">Status</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['status'] ?? ''); ?></div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-stone-500">Class</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['class_title'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-stone-500">Session</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['session_name'] ?? ''); ?></div>
                </div>
            </div>

            <div>
                <div class="text-sm text-stone-500">Schedule</div>
                <div class="font-medium text-stone-900">
                    <?= esc(formatScheduleFull(
                        $reg['session_date'] ?? '',
                        $reg['session_start'] ?? '',
                        $reg['session_end'] ?? '',
                        $reg['session_location'] ?? ''
                    )); ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-stone-500">Quantity</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['quantity'] ?? ''); ?></div>
                </div>
                <div>
                    <div class="text-sm text-stone-500">Unit Price</div>
                    <div class="font-medium text-stone-900"><?= esc($reg['unit_price_fmt'] ?? ''); ?></div>
                </div>
            </div>

            <div>
                <div class="text-sm text-stone-500">Registered At</div>
                <div class="font-medium text-stone-900"><?= esc($reg['registered_at'] ?? ''); ?></div>
            </div>
        </div>

        <?php
        // pesan WA
        $userName   = $user['name'] ?? '';
        $regId      = $reg['id'] ?? '';
        $totalText  = $reg['final_total_fmt'] ?? '';
        $classTitle = $reg['class_title'] ?? '';
        $sessName   = $reg['session_name'] ?? '';
        $sched      = formatScheduleFull($reg['session_date'] ?? '', $reg['session_start'] ?? '', $reg['session_end'] ?? '', $reg['session_location'] ?? '');
        $waNum      = $wa_number_intl ?? '';

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
            <a href="<?= base_url('/shop/class-sessions'); ?>" class="rounded bg-stone-600 px-4 py-2 text-white hover:bg-stone-700">
                Back to Classes
            </a>

            <?php if ($waUrl !== ''): ?>
                <a href="<?= esc($waUrl); ?>"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-2 rounded bg-green-500 px-4 py-2 text-white font-medium shadow 
                    hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-1">
                    <!-- SVG WhatsApp -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="h-5 w-5">
                        <path d="M16 .4C7.2.4.1 7.5.1 16.3c0 2.9.8 5.8 2.4 8.3L.3 31.6l7.2-2.2c2.3 1.3 5 2 7.7 2 8.8 0 15.9-7.1 15.9-15.9C31.1 7.5 24 .4 16 .4zm0 28.6c-2.5 0-5-.7-7.1-2l-.5-.3-4.3 1.3 1.4-4.2-.3-.5c-1.5-2.2-2.2-4.7-2.2-7.4 0-7.3 5.9-13.2 13.2-13.2s13.2 5.9 13.2 13.2-5.9 13.1-13.2 13.1zm7.3-9.8c-.4-.2-2.3-1.1-2.6-1.2s-.6-.2-.9.2c-.3.4-1.1 1.2-1.3 1.4-.2.2-.5.3-.9.1s-1.8-.7-3.4-2.1c-1.3-1.2-2.1-2.6-2.3-3-.2-.4 0-.6.2-.8.2-.2.4-.5.6-.7.2-.2.3-.4.4-.6.1-.2 0-.5 0-.7s-.9-2.1-1.2-2.9c-.3-.8-.6-.7-.9-.7h-.8c-.3 0-.7.1-1 .5s-1.3 1.3-1.3 3.1c0 1.8 1.3 3.6 1.5 3.8.2.2 2.6 4 6.3 5.6.9.4 1.6.6 2.1.8.9.3 1.8.3 2.5.2.8-.1 2.3-.9 2.6-1.7.3-.8.3-1.5.2-1.7-.1-.2-.3-.3-.7-.5z" />
                    </svg>
                    Kirim via WhatsApp
                </a>
            <?php endif; ?>
        </div>

        <p class="text-xs text-stone-400 mt-3">Tip: Ambil screenshot halaman ini sebagai bukti registrasi.</p>
    </div>
</div>
<?= $this->endSection(); ?>