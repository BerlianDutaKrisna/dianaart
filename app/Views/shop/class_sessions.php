<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Class Sessions<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Collections</h2>

            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:space-y-0 lg:gap-x-6">
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

                        // Gambar: session -> class -> file class_image -> "" (jangan paksa placeholder)
                        $img = $sessionImgUrl !== '' ? $sessionImgUrl
                            : ($classImgUrl !== '' ? $classImgUrl
                                : ($classImage !== '' ? base_url('uploads/classes/' . $classImage) : ''));

                        // Apakah punya sesi terdekat?
                        $hasSession = ($scheduleDate !== '');

                        // Subtitle aman
                        $when  = ($dateFmt !== '') ? $dateFmt : $scheduleDate;
                        $time  = ($timeFmt !== '') ? $timeFmt : trim(($startTime !== '' ? $startTime : '') . (($startTime !== '' && $endTime !== '') ? '–' . $endTime : ''));
                        $where = ($location !== '') ? (' @ ' . $location) : '';
                        $subtitle = $hasSession
                            ? trim("Next: " . trim($when) . ($time !== '' ? " • " . $time : '') . $where)
                            : 'No upcoming session';

                        // Badge/status aman
                        $badge = $statusBadge !== '' ? $statusBadge : ($hasSession ? 'Scheduled' : 'No sessions');

                        // Harga aman (gunakan price_fmt kalau ada; kalau tidak, coba dari class_price; selain itu "")
                        $priceText = $priceFmt !== '' ? $priceFmt : ($classPrice !== '' ? ('Rp ' . number_format((float)$classPrice, 0, ',', '.')) : '');

                        // Count aman
                        $safeCounts = isset($sessionCounts) && is_array($sessionCounts) ? $sessionCounts : [];
                        $count = (int) ($safeCounts[$classId] ?? ($totalSessions !== '' ? $totalSessions : 0));

                        // Link tujuan aman (boleh dikosongkan)
                        $href = ($hasSession && $sessionId !== '') ? base_url('class-sessions/' . $sessionId)
                            : ($classId !== '' ? base_url('classes/show/' . $classId) : '');
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
                                    <?php if ($booked !== '' || $remaining !== ''): ?>
                                        (<?php if ($booked !== ''): ?>Booked: <?= esc($booked); ?><?php endif; ?><?= ($booked !== '' && $remaining !== '') ? ', ' : '' ?><?php if ($remaining !== ''): ?>Remaining: <?= esc($remaining); ?><?php endif; ?>)
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">Belum ada kelas aktif.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>