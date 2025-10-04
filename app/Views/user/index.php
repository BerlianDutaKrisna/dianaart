<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Users<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 rounded-md bg-green-50 p-4 text-green-700">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Daftar User</h1>
        <a href="<?= base_url('register'); ?>"
            class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">+ Tambah User</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Birth Date</th>
                    <th class="px-4 py-3">Age</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <?php
                        $birth = $u['birth_date'] ?? null;
                        $age   = '—';
                        if ($birth) {
                            $birthDate = new DateTime($birth);
                            $today     = new DateTime();
                            $age       = $today->diff($birthDate)->y . ' th';
                        }
                        ?>
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-3"><?= esc($u['id']) ?></td>
                            <td class="px-4 py-3"><?= esc($u['name']) ?></td>
                            <td class="px-4 py-3"><?= esc($u['email']) ?></td>
                            <td class="px-4 py-3"><?= esc($u['phone'] ?? '—') ?></td>
                            <td class="px-4 py-3"><?= $birth ? esc($birth) : '—' ?></td>
                            <td class="px-4 py-3"><?= esc($age) ?></td>
                            <td class="px-4 py-3">
                                <?php if ($u['role'] === 'admin'): ?>
                                    <span class="rounded bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">Admin</span>
                                <?php else: ?>
                                    <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">Customer</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="<?= base_url('user/edit/' . $u['id']); ?>"
                                    class="text-indigo-600 hover:underline">Edit</a>
                                <form action="<?= base_url('user/delete/' . $u['id']); ?>" method="post" class="inline"
                                    onsubmit="return confirm('Hapus user ini?')">
                                    <?= csrf_field(); ?>
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">Belum ada user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection(); ?>