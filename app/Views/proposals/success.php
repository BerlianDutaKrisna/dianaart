<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Proposal Success<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-stone-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-stone-900 mb-4">Proposal Submitted</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                <?= esc($message); ?>
            </div>
        <?php endif; ?>

        <?php
        // helper untuk format jadwal (reuse dari halaman registrasi)
        if (!function_exists('formatScheduleFull')) {
            function formatScheduleFull(?string $date, ?string $start, ?string $end, ?string $location): string
            {
                $date     = $date ?: '';
                $start    = $start ?: '';
                $end      = $end ?: '';
                $location = $location ?: '';

                $tz = new DateTimeZone('Asia/Jakarta');

                $dtStart = null;
                $dtEnd   = null;
                if ($date !== '') {
                    $dtStart = new DateTimeImmutable(trim($date . ' ' . ($start !== '' ? $start : '00:00')), $tz);
                    if ($end !== '') {
                        $dtEnd = new DateTimeImmutable(trim($date . ' ' . $end), $tz);
                    }
                }

                // format tanggal (locale Indonesia jika tersedia)
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

                $where = $location !== '' ? (' @ ' . $location) : '';

                $pieces = [];
                if ($dayDate !== '')  $pieces[] = $dayDate;
                if ($timePart !== '') $pieces[] = $timePart;

                return !empty($pieces) ? (implode(' • ', $pieces) . $where) : ($where !== '' ? ltrim($where, ' ') : '');
            }
        }

        // siapkan data tampilan
        $userName   = session('user_name') ?? '';
        $proposalId = $proposal['id'] ?? '';
        $status     = $proposal['status'] ?? 'pending';
        $title      = $proposal['title'] ?? '';
        $schedule   = formatScheduleFull(
            $proposal['schedule_date'] ?? '',
            $proposal['start_time']    ?? '',
            $proposal['end_time']      ?? '',
            $proposal['location']      ?? ''
        );
        $createdAtFmt = !empty($proposal['created_at']) ? date('d M Y H:i', strtotime($proposal['created_at'])) : '';

        // nomor WA (format internasional tanpa +/spasi, mis: 62812xxxx)
        $waNum = $wa_number_intl ?? ''; // pastikan variabel ini tersedia di layout/global
        // compose pesan WA
        $msg  = "Hai, saya baru saja mengajukan proposal kelas.%0A";
        if ($userName !== '') $msg .= "Nama: {$userName}%0A";
        $msg .= "ID Proposal: {$proposalId}%0A";
        $msg .= "Judul: {$title}%0A";
        if ($schedule !== '') $msg .= "Jadwal: {$schedule}%0A";
        $msg .= "Status: {$status}%0A";
        $msg .= "Terima kasih.";
        $waUrl = $waNum !== '' ? "https://wa.me/{$waNum}?text={$msg}" : '';
        ?>

        <div class="rounded border bg-white p-4 space-y-3">
            <div>
                <div class="text-sm text-stone-500">Nama</div>
                <div class="font-medium text-stone-900"><?= esc($userName); ?></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-stone-500">ID Proposal</div>
                    <div class="font-medium text-stone-900"><?= esc($proposalId); ?></div>
                </div>
                <div>
                    <div class="text-sm text-stone-500">Status</div>
                    <div class="font-medium text-stone-900"><?= esc(ucfirst($status)); ?></div>
                </div>
            </div>

            <div>
                <div class="text-sm text-stone-500">Judul</div>
                <div class="font-medium text-stone-900"><?= esc($title); ?></div>
            </div>

            <div>
                <div class="text-sm text-stone-500">Jadwal</div>
                <div class="font-medium text-stone-900"><?= esc($schedule); ?></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="text-sm text-stone-500">Diajukan Pada</div>
                    <div class="font-medium text-stone-900"><?= esc($createdAtFmt); ?></div>
                </div>
            </div>

            <?php if (!empty($proposal['image_url'])): ?>
                <div class="pt-2">
                    <img src="<?= esc($proposal['image_url']); ?>" alt="Proposal Image" class="rounded border">
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <a href="<?= base_url('/shop/class-sessions'); ?>" class="rounded bg-stone-600 px-4 py-2 text-white hover:bg-stone-700">
                Back to Classes
            </a>

            <?php if ($waUrl !== ''): ?>
                <a href="<?= esc($waUrl); ?>"
                    target="_blank" rel="noopener"
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

        <p class="text-xs text-stone-400 mt-3">Tip: Simpan/screenshot halaman ini sebagai bukti pengajuan.</p>
    </div>
</div>
<?= $this->endSection(); ?>