<?= $this->extend('template/layout'); ?>
<?= $this->section('title'); ?>Login<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Login</h2>

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

    <form action="/login/auth" method="post" class="space-y-4">
        <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>"
            class="w-full border rounded p-2" required>
        <input type="password" name="password" placeholder="Password"
            class="w-full border rounded p-2" required>
        <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded hover:bg-pink-700 transition">
            Login
        </button>
    </form>
    <p class="mt-4 text-sm text-gray-600 text-center">
        Belum punya akun? <a href="/register" class="text-pink-500 hover:underline">Daftar</a>
    </p>
</div>
<?= $this->endSection(); ?>