<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Dashboard — DianaArt<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="min-h-full">

    <!-- Page header -->
    <header class="relative bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1>
        </div>
    </header>

    <!-- Main content -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <!-- Your content -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg bg-white p-6 shadow">Card 1</div>
                <div class="rounded-lg bg-white p-6 shadow">Card 2</div>
                <div class="rounded-lg bg-white p-6 shadow">Card 3</div>
            </div>
        </div>
    </main>
</div>
<?= $this->endSection(); ?>