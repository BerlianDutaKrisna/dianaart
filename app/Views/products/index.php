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
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Products</h1>
    <a href="/products/create" class="bg-pink-500 text-white px-4 py-2 rounded">+ Add Product</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 text-green-700 p-2 mt-4 rounded">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full mt-4 border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2">Image</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Flower Type</th>
                <th class="px-4 py-2">Color</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Stock</th>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr class="border-t">
                    <td class="px-4 py-2">
                        <?php if ($p['image']): ?>
                            <img src="<?= base_url('uploads/products/' . $p['image']) ?>" class="h-16 w-16 object-cover rounded">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2"><?= esc($p['name']) ?></td>
                    <td class="px-4 py-2"><?= esc($p['flower_type']) ?></td>
                    <td class="px-4 py-2"><?= esc($p['flower_color']) ?></td>
                    <td class="px-4 py-2">$<?= esc($p['price']) ?></td>
                    <td class="px-4 py-2"><?= esc($p['stock']) ?></td>
                    <td class="px-4 py-2"><?= esc($p['category_name']) ?></td>
                    <td class="px-4 py-2">
                        <a href="/products/show/<?= $p['id'] ?>" class="text-blue-600">View</a> |
                        <a href="/products/edit/<?= $p['id'] ?>" class="text-yellow-600">Edit</a> |
                        <a href="/products/delete/<?= $p['id'] ?>" class="text-red-600" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>