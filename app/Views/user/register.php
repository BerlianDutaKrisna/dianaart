<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Register<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Register</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/user/save" method="post" class="space-y-4">
        <input type="text" name="name" placeholder="Nama" value="<?= old('name') ?>"
            class="w-full border rounded p-2" required>
        <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>"
            class="w-full border rounded p-2" required>
        <input type="password" name="password" placeholder="Password"
            class="w-full border rounded p-2" required>
        <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded hover:bg-pink-700 transition">
            Register
        </button>
    </form>
    <p class="mt-4 text-sm text-gray-600 text-center">
        Sudah punya akun? <a href="/login" class="text-pink-500 hover:underline">Login</a>
    </p>
</div>
<?= $this->endSection(); ?>