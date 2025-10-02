<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Edit user<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php
// Helper kecil untuk tanggal -> Y-m-d
$birthYmd = '';
if (!empty($user['birth_date'])) {
    // Terima format 'Y-m-d' atau 'Y-m-d H:i:s'
    $birthYmd = substr($user['birth_date'], 0, 10);
}
?>

<div class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-md">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-md bg-green-50 p-3 text-green-700">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-3 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- Validation errors -->
    <?php if (isset($validation) && $validation->getErrors()): ?>
        <div class="mb-4 rounded-md bg-red-50 p-3 text-red-700">
            <?= $validation->listErrors(); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('user/update/' . $user['id']); ?>" method="post" class="space-y-4">
        <?= csrf_field(); ?>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Name</label>
            <input
                type="text"
                name="name"
                value="<?= esc(old('name', $user['name'] ?? '')) ?>"
                class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                required>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
            <input
                type="email"
                name="email"
                value="<?= esc(old('email', $user['email'] ?? '')) ?>"
                class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                required>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="birth_date" class="mb-1 block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <input
                    type="date"
                    id="birth_date"
                    name="birth_date"
                    value="<?= esc(old('birth_date', $birthYmd)) ?>"
                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                    required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Nomor HP</label>
                <input
                    type="text"
                    name="phone"
                    placeholder="08xxxxxxxxxx"
                    value="<?= esc(old('phone', $user['phone'] ?? '')) ?>"
                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                    required>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Password <span class="text-gray-400">(kosongkan jika tidak ingin mengganti)</span></label>
            <input
                type="password"
                name="password"
                class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                placeholder="••••••••">
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <?php
            $role = session()->get('user_role');
            $cancelUrl = ($role === 'admin') ? base_url('/user') : base_url('/');
            ?>
            <a href="<?= $cancelUrl; ?>"
                class="rounded bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200">Cancel</a>
            <button type="submit" class="rounded bg-pink-600 px-4 py-2 text-white hover:bg-pink-700">Update</button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>