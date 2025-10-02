<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Class Sessions<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-16 sm:py-24 lg:max-w-none lg:py-32">
            <h2 class="text-2xl font-bold text-gray-900">Collections</h2>

            <div class="mt-6 space-y-12 lg:grid lg:grid-cols-3 lg:space-y-0 lg:gap-x-6">
                <?php if (!empty($cards)): ?>
                    <?php foreach ($cards as $card): ?>
                        <?php
                        $img = !empty($card['class_image'])
                            ? base_url('uploads/classes/' . $card['class_image'])
                            : 'https://tailwindcss.com/plus-assets/img/ecommerce-images/home-page-02-edition-01.jpg';

                        $title = esc($card['class_title'] ?? 'Untitled Class');
                        $count = (int) ($sessionCounts[$card['class_id']] ?? 0);

                        // subtitle / deskripsi kecil di bawah title:
                        if ($count > 0 && !empty($card['schedule_date'])) {
                            $subtitle = 'Next: ' . esc($card['schedule_date']) .
                                (!empty($card['start_time']) ? (' • ' . esc($card['start_time']) . (!empty($card['end_time']) ? ('–' . esc($card['end_time'])) : '')) : '') .
                                (!empty($card['location']) ? (' @ ' . esc($card['location'])) : '');
                        } else {
                            $subtitle = 'No upcoming session';
                        }

                        // link tujuan (ganti sesuai kebutuhanmu: ke detail class atau ke daftar session per class)
                        $href = base_url('classes/show/' . $card['class_id']); // jika kamu punya halaman detail class
                        // Atau bisa juga ke listing session per class (buat route/filter sendiri):
                        // $href = base_url('class-sessions?class_id=' . $card['class_id']);
                        ?>
                        <div class="group relative">
                            <img
                                src="<?= $img; ?>"
                                alt="<?= $title; ?>"
                                class="w-full rounded-lg bg-white object-cover group-hover:opacity-75 max-sm:h-80 sm:aspect-[2/1] lg:aspect-square" />

                            <h3 class="mt-6 text-sm text-gray-500">
                                <a href="<?= $href; ?>">
                                    <span class="absolute inset-0"></span>
                                    <?= $title; ?>
                                </a>
                            </h3>

                            <p class="text-base font-semibold text-gray-900">
                                <?= esc($subtitle); ?>
                                <?php if ($count > 1): ?>
                                    <span class="text-sm text-gray-500"> • <?= $count; ?> sessions total</span>
                                <?php endif; ?>
                            </p>
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