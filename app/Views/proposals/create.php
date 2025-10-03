<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Buat Proposal Kelas<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <div class="mx-auto max-w-xl px-4 py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Usulkan Kelas Baru</h1>

        <?php $errors = session('errors') ?? []; ?>

        <?php if (!empty($errors['general'])): ?>
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <?= esc($errors['general']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('proposals'); ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700">Judul Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?= esc(old('title')); ?>" class="mt-1 block w-full rounded border-gray-300" required />
                <?php if (!empty($errors['title'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['title']); ?></p><?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="4" class="mt-1 block w-full rounded border-gray-300"><?= esc(old('description')); ?></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="schedule_date" value="<?= esc(old('schedule_date')); ?>" class="mt-1 block w-full rounded border-gray-300" required />
                    <?php if (!empty($errors['schedule_date'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['schedule_date']); ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="<?= esc(old('location')); ?>" class="mt-1 block w-full rounded border-gray-300" required />
                    <?php if (!empty($errors['location'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['location']); ?></p><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Waktu Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="<?= esc(old('start_time')); ?>" class="mt-1 block w-full rounded border-gray-300" required />
                    <?php if (!empty($errors['start_time'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['start_time']); ?></p><?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Waktu Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="<?= esc(old('end_time')); ?>" class="mt-1 block w-full rounded border-gray-300" required />
                    <?php if (!empty($errors['end_time'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['end_time']); ?></p><?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga Perkiraan</label>
                    <input type="number" name="price" min="0" step="1" value="<?= esc(old('price', '0')); ?>" class="mt-1 block w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gambar (opsional)</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full" />
                    <?php if (!empty($errors['image'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['image']); ?></p><?php endif; ?>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Kirim Usulan</button>
                <a href="<?= base_url('class-sessions'); ?>" class="text-sm text-gray-600 hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>