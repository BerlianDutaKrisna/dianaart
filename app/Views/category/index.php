<?= $this->extend('template/layout'); ?>

<?= $this->section('title'); ?>Dashboard<?= $this->endSection(); ?>

<?= $this->section('navbar'); ?>
<nav class="space-x-4 text-sm font-medium">
    <a href="/dashboard" class="text-gray-700 hover:text-pink-600">Dashboard</a>
    <a href="/category" class="text-gray-700 hover:text-pink-600">Categories</a>
    <a href="/products" class="text-gray-700 hover:text-pink-600">Products</a>
    <a href="/logout" class="text-gray-700 hover:text-pink-600">Logout</a>
</nav>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Category List</h1>
        <a href="<?= base_url('category/create'); ?>"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            + Add Category
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($categories) && is_array($categories)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Image</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $no = 1;
                    foreach ($categories as $category): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= $no++; ?></td>
                            <td class="px-6 py-4 text-sm text-gray-800"><?= esc($category['name']); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-600"><?= esc($category['description']); ?></td>
                            <td class="px-6 py-4">
                                <?php if ($category['image']): ?>
                                    <img src="<?= base_url('uploads/categories/' . $category['image']); ?>"
                                        alt="<?= esc($category['name']); ?>"
                                        class="h-16 w-16 object-cover rounded">
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="<?= base_url('category/edit/' . $category['id']); ?>"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">Edit</a>
                                <a href="<?= base_url('category/delete/' . $category['id']); ?>"
                                    onclick="return confirm('Are you sure?');"
                                    class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No categories found.</p>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>