<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Edit Class Session<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900">Edit Class Session</h1>

    <?php $errors = session('errors') ?? []; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('class-sessions/update/' . $session['id']); ?>" method="post" class="space-y-5 bg-white p-6 rounded-lg border">
        <?= csrf_field(); ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Class</label>
            <select name="class_id" class="mt-1 w-full rounded border-gray-300" required>
                <option value="">-- Choose Class --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id']; ?>" <?= old('class_id', $session['class_id']) == $c['id'] ? 'selected' : '' ?>>
                        <?= esc($c['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['class_id'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['class_id']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="<?= old('name', $session['name']) ?>" class="mt-1 w-full rounded border-gray-300" required>
            <?php if (!empty($errors['name'])): ?><p class="mt-1 text-sm text-red-600"><?= esc($errors['name']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded border-gray-300"><?= old('description', $session['description']) ?></textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Level</label>
                <input type="text" name="level" value="<?= old('level', $session['level']) ?>" class="mt-1 w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Capacity</label>
                <input type="number" name="capacity" value="<?= old('capacity', $session['capacity']) ?>" min="0" class="mt-1 w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <input type="text" name="status" value="<?= old('status', $session['status'] ?: 'Scheduled') ?>" class="mt-1 w-full rounded border-gray-300" placeholder="Scheduled / Cancelled / Completed">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Schedule Date</label>
                <input type="date" name="schedule_date" value="<?= old('schedule_date', $session['schedule_date']) ?>" class="mt-1 w-full rounded border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" name="start_time" value="<?= old('start_time', $session['start_time']) ?>" class="mt-1 w-full rounded border-gray-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" name="end_time" value="<?= old('end_time', $session['end_time']) ?>" class="mt-1 w-full rounded border-gray-300" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Location</label>
            <input type="text" name="location" value="<?= old('location', $session['location']) ?>" class="mt-1 w-full rounded border-gray-300">
        </div>

        <div class="pt-2">
            <a href="<?= base_url('class-sessions'); ?>" class="rounded border px-4 py-2 text-gray-700 hover:bg-gray-50">Back</a>
            <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>