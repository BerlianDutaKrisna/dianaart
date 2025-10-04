<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Class Sessions<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Collections</h2>

            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:space-y-0 lg:gap-x-6">
                <?php
                $loginUserId = session('user_id') ?? null;
                $proposalHref = empty($loginUserId) ? base_url('login') : base_url('proposals/create');
                ?>

                <!-- PROPOSAL CARD: selalu tampil -->
                <div class="group relative border-2 border-dashed border-indigo-300 rounded-lg p-4 bg-white flex flex-col items-center justify-center text-center">
                    <a href="<?= esc($proposalHref); ?>" class="absolute inset-0" aria-label="Create Proposal"></a>
                    <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold text-gray-900">Usulkan Kelas Baru</h3>
                    <p class="mt-1 text-sm text-gray-600">Punya ide kelas? Kirim usulanmu di sini.</p>
                    <div class="mt-4">
                    </div>
                </div>

                <?php if (!empty($cards) && is_array($cards)): ?>
                    <?php foreach ($cards as $card): ?>
                        <?php
                        // Safe getters (fallback ke "")
                        $classId         = $card['class_id']            ?? '';
                        $sessionId       = $card['session_id']          ?? '';
                        $classTitle      = $card['class_title']         ?? '';
                        $sessionName     = $card['session_name']        ?? ($card['name'] ?? '');
                        $statusBadge     = $card['status_badge']        ?? '';
                        $sessionLevel    = $card['session_level']       ?? ($card['level'] ?? '');
                        $sessionCapacity = $card['session_capacity']    ?? ($card['capacity'] ?? '');
                        $booked          = $card['booked']              ?? '';
                        $remaining       = $card['remaining']           ?? '';
                        $location        = $card['location']            ?? '';
                        $scheduleDate    = $card['schedule_date']       ?? '';
                        $dateFmt         = $card['date_fmt']            ?? '';
                        $startTime       = $card['start_time']          ?? '';
                        $endTime         = $card['end_time']            ?? '';
                        $timeFmt         = $card['time_fmt']            ?? '';
                        $classPrice      = $card['class_price']         ?? '';
                        $priceFmt        = $card['price_fmt']           ?? '';
                        $totalSessions   = $card['total_sessions']      ?? '';
                        $sessionImgUrl   = $card['session_image_url']   ?? '';
                        $classImgUrl     = $card['class_image_url']     ?? '';
                        $classImage      = $card['class_image']         ?? '';

                        // Gambar: session -> class -> file class_image -> ""
                        $img = $sessionImgUrl !== '' ? $sessionImgUrl
                            : ($classImgUrl !== '' ? $classImgUrl
                                : ($classImage !== '' ? base_url('uploads/classes/' . $classImage) : ''));

                        // Apakah punya sesi terdekat?
                        $hasSession = ($scheduleDate !== '');

                        // === Subtitle lengkap: Hari, DD MMM YYYY • HH:mm–HH:mm @ Lokasi ===
                        $hasSession = ($scheduleDate !== '');

                        $subtitle = 'Upcoming session'; // default
                        if ($hasSession) {
                            // Build DateTime dari input (aman walau jam kosong)
                            $tz = new DateTimeZone('Asia/Jakarta');

                            $dtStart = null;
                            $dtEnd   = null;
                            if (!empty($scheduleDate)) {
                                $dtStart = new DateTimeImmutable(trim($scheduleDate . ' ' . ($startTime ?: '00:00')), $tz);
                                if (!empty($endTime)) {
                                    $dtEnd = new DateTimeImmutable(trim($scheduleDate . ' ' . $endTime), $tz);
                                }
                            }

                            // Format tanggal (pakai Intl kalau tersedia)
                            $dayDate = '';
                            if ($dtStart) {
                                if (class_exists('IntlDateFormatter')) {
                                    // Contoh hasil: Sabtu, 04 Okt 2025
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
                                    // Fallback tanpa ekstensi intl
                                    $dayDate = $dtStart->format('l, d M Y');
                                }
                            } else {
                                // fallback ke nilai mentah jika benar-benar tak bisa parse
                                $dayDate = $dateFmt !== '' ? $dateFmt : $scheduleDate;
                            }

                            // Format waktu
                            $timePart = '';
                            if ($dtStart && !empty($startTime)) {
                                if (class_exists('IntlDateFormatter')) {
                                    // Format jam 24h dengan titik: 09.00 / 09.00–11.30
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
                            } else {
                                // fallback ke timeFmt/mentah
                                $timePart = ($timeFmt !== '') ? $timeFmt
                                    : trim(($startTime !== '' ? $startTime : '') . (($startTime !== '' && $endTime !== '') ? '–' . $endTime : ''));
                            }

                            // Lokasi (opsional)
                            $where = ($location !== '') ? (' @ ' . $location) : '';

                            // Rakit final
                            $pieces = [];
                            if ($dayDate !== '')  $pieces[] = $dayDate;
                            if ($timePart !== '') $pieces[] = $timePart;

                            $subtitle = !empty($pieces)
                                ? ('Next: ' . implode(' • ', $pieces) . $where)
                                : ('Next: ' . ($where !== '' ? ltrim($where, ' ') : ''));
                        }

                        // Badge/status aman
                        $badge = $statusBadge !== '' ? $statusBadge : ($hasSession ? 'Scheduled' : 'No sessions');

                        // Harga aman
                        $priceText = $priceFmt !== '' ? $priceFmt : ($classPrice !== '' ? ('Rp ' . number_format((float)$classPrice, 0, ',', '.')) : '');

                        // Count aman
                        $safeCounts = isset($sessionCounts) && is_array($sessionCounts) ? $sessionCounts : [];
                        $count = (int) ($safeCounts[$classId] ?? ($totalSessions !== '' ? $totalSessions : 0));

                        // Link tujuan aman
                        $userId = session('user_id') ?? null;
                        $remaining = (int) ($card['remaining'] ?? ($card['session_capacity'] ?? 0));

                        if (empty($userId)) {
                            $href = base_url('login');
                        } elseif (!$hasSession || $sessionId === '') {
                            $href = "javascript:alert('Kelas ini belum dibuka.');void(0);";
                        } elseif ($remaining <= 0) {
                            $href = "javascript:alert('Maaf kelas sudah penuh.');void(0);";
                        } else {
                            $href = base_url('registrations/create/' . $sessionId);
                        }
                        ?>
                        <div class="group relative">
                            <?php if ($href !== ''): ?><a href="<?= esc($href); ?>"><?php endif; ?>

                                <?php if ($img !== ''): ?>
                                    <img
                                        src="<?= esc($img); ?>"
                                        alt="<?= esc($classTitle !== '' ? $classTitle : ($sessionName !== '' ? $sessionName : '')); ?>"
                                        class="w-full rounded-lg bg-white object-cover group-hover:opacity-75 max-sm:h-80 sm:aspect-[2/1] lg:aspect-square" />
                                <?php endif; ?>

                                <?php if ($href !== ''): ?></a><?php endif; ?>

                            <?php if ($classTitle !== ''): ?>
                                <h3 class="mt-6 text-sm text-gray-500">
                                    <?php if ($href !== ''): ?><a href="<?= esc($href); ?>"><span class="absolute inset-0"></span><?php endif; ?>
                                        <?= esc($classTitle); ?>
                                        <?php if ($href !== ''): ?></a><?php endif; ?>
                                </h3>
                            <?php endif; ?>

                            <div class="mt-1 flex items-center gap-2">
                                <?php if ($badge !== ''): ?>
                                    <span class="inline-block rounded border px-2 py-0.5 text-xs text-gray-700">
                                        <?= esc($badge); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($sessionName !== ''): ?>
                                    <span class="text-xs text-gray-500"><?= esc($sessionName); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($subtitle !== ''): ?>
                                <p class="mt-1 text-base font-semibold text-gray-900">
                                    <?= esc($subtitle); ?>
                                    <?php if ($count > 1): ?>
                                        <span class="text-sm text-gray-500"> • <?= esc($count); ?> sessions total</span>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($priceText !== ''): ?>
                                <p class="text-sm text-gray-700"><?= esc($priceText); ?></p>
                            <?php endif; ?>

                            <?php if ($sessionLevel !== ''): ?>
                                <p class="text-sm text-gray-500">Level: <?= esc($sessionLevel); ?></p>
                            <?php endif; ?>

                            <?php if ($sessionCapacity !== '' || $booked !== '' || $remaining !== ''): ?>
                                <p class="text-sm text-gray-500">
                                    <?php if ($sessionCapacity !== ''): ?>
                                        Capacity: <?= esc($sessionCapacity); ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>