<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Register<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Registration</h1>

        <?php
        $errors = session('errors') ?? [];

        // Helper: format jadwal lengkap "Hari, DD MMM YYYY • HH:mm–HH:mm @ Lokasi"
        if (!function_exists('formatScheduleFull')) {
            function formatScheduleFull(?string $date, ?string $start, ?string $end, ?string $location): string
            {
                $date = $date ?: '';
                $start = $start ?: '';
                $end = $end ?: '';
                $location = $location ?: '';

                $tz = new DateTimeZone('Asia/Jakarta');

                $dtStart = null;
                $dtEnd = null;
                if ($date !== '') {
                    $dtStart = new DateTimeImmutable(trim($date . ' ' . ($start !== '' ? $start : '00:00')), $tz);
                    if ($end !== '') $dtEnd = new DateTimeImmutable(trim($date . ' ' . $end), $tz);
                }

                // Tanggal
                if ($dtStart) {
                    if (class_exists('IntlDateFormatter')) {
                        $fmtDate = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, $tz->getName(), IntlDateFormatter::GREGORIAN, 'EEEE, dd MMM yyyy');
                        $dayDate = $fmtDate->format($dtStart);
                    } else {
                        $dayDate = $dtStart->format('l, d M Y');
                    }
                } else {
                    $dayDate = $date;
                }

                // Waktu
                $timePart = '';
                if ($dtStart && $start !== '') {
                    if (class_exists('IntlDateFormatter')) {
                        $fmtTime = new IntlDateFormatter('id_ID', IntlDateFormatter::NONE, IntlDateFormatter::NONE, $tz->getName(), IntlDateFormatter::GREGORIAN, 'HH:mm');
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

                // Lokasi
                $where = $location !== '' ? (' @ ' . $location) : '';

                $pieces = [];
                if ($dayDate !== '')  $pieces[] = $dayDate;
                if ($timePart !== '') $pieces[] = $timePart;

                return !empty($pieces) ? (implode(' • ', $pieces) . $where) : ($where !== '' ? ltrim($where, ' ') : '');
            }
        }
        ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <?= esc($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('registrations/store'); ?>" method="post" class="space-y-6">
            <?= csrf_field(); ?>

            <?php if (!empty($selectedSession)): ?>
                <input type="hidden" name="session_id" value="<?= esc($selectedSession['id'] ?? ''); ?>">

                <div class="rounded border bg-white p-4 space-y-3">
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
                            <?= esc(formatScheduleFull(
                                $selectedSession['schedule_date'] ?? '',
                                $selectedSession['start_time'] ?? '',
                                $selectedSession['end_time'] ?? '',
                                $selectedSession['location'] ?? ''
                            )); ?>
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
                    <select name="session_id"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm
                                focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1
                                transition duration-150 ease-in-out">
                        <option value="">— Select —</option>
                        <?php foreach (($sessions ?? []) as $s): ?>
                            <?php
                            $optTitle = trim(($s['class_title'] ?? '') . ' — ' . ($s['name'] ?? ''));
                            $optSched = formatScheduleFull(
                                $s['schedule_date'] ?? '',
                                $s['start_time'] ?? '',
                                $s['end_time'] ?? '',
                                $s['location'] ?? ''
                            );
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

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="rounded bg-stone-600 px-4 py-2 text-white hover:bg-stone-700
                            focus:outline-none focus:ring-2 focus:ring-stone-500 focus:ring-offset-1">
                    Confirm Registration
                </button>
                <a href="<?= base_url('class-sessions'); ?>" class="text-sm text-gray-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>