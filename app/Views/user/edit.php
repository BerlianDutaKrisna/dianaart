<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Edit User<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/dashboard" class="text-gray-700 hover:text-pink-600">Dashboard</a>
    <a href="/category" class="text-gray-700 hover:text-pink-600">Categories</a>
    <a href="/products" class="text-gray-700 hover:text-pink-600">Products</a>
    <a href="/user" class="text-gray-700 hover:text-pink-600">Users</a>
    <a href="/logout" class="text-gray-700 hover:text-pink-600">Logout</a>
</nav>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit User</h1>

    <?php if (isset($validation)): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
            <?= $validation->listErrors(); ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('user/update/' . $user['id']); ?>" method="post">
        <?= csrf_field(); ?>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="<?= old('name', $user['name']); ?>"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="<?= old('email', $user['email']); ?>"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="customer" <?= old('role', $user['role']) == 'customer' ? 'selected' : ''; ?>>Customer</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">Password (kosongkan jika tidak ingin mengganti)</label>
            <input type="password" name="password"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
        </div>

        <div class="flex justify-end space-x-2">
            <a href="<?= base_url('user'); ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
            <button type="submit"
                class="px-4 py-2 bg-pink-600 text-white rounded hover:bg-pink-700">Update</button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>