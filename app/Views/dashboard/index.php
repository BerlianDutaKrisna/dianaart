<?= $this->extend('template/app_layout'); ?>
<?= $this->section('title'); ?>Dashboard — DianaArt<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="bg-stone-50 min-h-screen">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-12">

        <h1 class="text-2xl font-bold text-stone-900">Data Overview</h1>

        <!-- USERS -->
        <section>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">Users</h2>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border">ID</th>
                            <th class="px-3 py-2 border">Name</th>
                            <th class="px-3 py-2 border">Email</th>
                            <th class="px-3 py-2 border">Phone</th>
                            <th class="px-3 py-2 border">Birth Date</th>
                            <th class="px-3 py-2 border">Role</th>
                            <th class="px-3 py-2 border">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr class="border-b">
                                <td class="px-3 py-2 border"><?= esc($u['id']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($u['name']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($u['email']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($u['phone'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= esc($u['birth_date'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= esc($u['role']); ?></td>
                                <td class="px-3 py-2 border"><?= !empty($u['created_at']) ? date('d M Y H:i', strtotime($u['created_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CLASSES -->
        <section>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">Classes</h2>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border">ID</th>
                            <th class="px-3 py-2 border">Title</th>
                            <th class="px-3 py-2 border">Description</th>
                            <th class="px-3 py-2 border">Price</th>
                            <th class="px-3 py-2 border">Active</th>
                            <th class="px-3 py-2 border">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($classes as $c): ?>
                            <tr class="border-b">
                                <td class="px-3 py-2 border"><?= esc($c['id']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($c['title']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($c['description'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= 'Rp ' . number_format((float)($c['price'] ?? 0), 0, ',', '.'); ?></td>
                                <td class="px-3 py-2 border">
                                    <span class="px-2 py-1 text-xs rounded <?= !empty($c['is_active']) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'; ?>">
                                        <?= !empty($c['is_active']) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 border"><?= !empty($c['created_at']) ? date('d M Y H:i', strtotime($c['created_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CLASS SESSIONS -->
        <section>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">Class Sessions</h2>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border">ID</th>
                            <th class="px-3 py-2 border">Class</th>
                            <th class="px-3 py-2 border">Session</th>
                            <th class="px-3 py-2 border">Level</th>
                            <th class="px-3 py-2 border">Capacity</th>
                            <th class="px-3 py-2 border">Schedule</th>
                            <th class="px-3 py-2 border">Location</th>
                            <th class="px-3 py-2 border">Status</th>
                            <th class="px-3 py-2 border">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sessions as $s): ?>
                            <tr class="border-b">
                                <td class="px-3 py-2 border"><?= esc($s['id']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($s['class_title'] ?? ('#' . $s['class_id'])); ?></td>
                                <td class="px-3 py-2 border"><?= esc($s['name']); ?></td>
                                <td class="px-3 py-2 border"><?= esc($s['level'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= esc($s['capacity'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border">
                                    <?php
                                    $date = $s['schedule_date'] ?? '';
                                    $st   = $s['start_time'] ?? '';
                                    $et   = $s['end_time'] ?? '';
                                    $dateFmt = $date ? date('d M Y', strtotime($date)) : '-';
                                    $timeFmt = ($st && $et) ? (date('H:i', strtotime($st)) . ' - ' . date('H:i', strtotime($et))) : '-';
                                    echo esc(trim($dateFmt . ' • ' . $timeFmt, " •"));
                                    ?>
                                </td>
                                <td class="px-3 py-2 border"><?= esc($s['location'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= esc($s['status'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= !empty($s['created_at']) ? date('d M Y H:i', strtotime($s['created_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- REGISTRATIONS -->
        <section>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">Registrations</h2>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border">ID</th>
                            <th class="px-3 py-2 border">User</th>
                            <th class="px-3 py-2 border">Class</th>
                            <th class="px-3 py-2 border">Session</th>
                            <th class="px-3 py-2 border">Qty</th>
                            <th class="px-3 py-2 border">Unit Price</th>
                            <th class="px-3 py-2 border">Subtotal</th>
                            <th class="px-3 py-2 border">Final Total</th>
                            <th class="px-3 py-2 border">Status</th>
                            <th class="px-3 py-2 border">Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $r): ?>
                            <tr class="border-b">
                                <td class="px-3 py-2 border"><?= esc($r['id']); ?></td>
                                <td class="px-3 py-2 border">
                                    <?= esc(($r['user_name'] ?? '') !== '' ? $r['user_name'] : '#' . $r['user_id']); ?><br>
                                    <span class="text-xs text-gray-500"><?= esc($r['user_email'] ?? ''); ?></span>
                                </td>
                                <td class="px-3 py-2 border"><?= esc($r['class_title'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border">
                                    <?= esc($r['session_name'] ?? '-'); ?><br>
                                    <span class="text-xs text-gray-500">
                                        <?php
                                        $d = $r['session_date'] ?? '';
                                        $st = $r['session_start'] ?? '';
                                        $et = $r['session_end'] ?? '';
                                        $loc = $r['session_location'] ?? '';
                                        $dFmt = $d ? date('d M Y', strtotime($d)) : '';
                                        $tFmt = ($st && $et) ? (date('H:i', strtotime($st)) . '–' . date('H:i', strtotime($et))) : '';
                                        echo esc(trim($dFmt . ' • ' . $tFmt . ($loc ? ' @ ' . $loc : ''), ' •'));
                                        ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 border"><?= esc($r['quantity'] ?? 1); ?></td>
                                <td class="px-3 py-2 border"><?= 'Rp ' . number_format((float)($r['unit_price'] ?? 0), 0, ',', '.'); ?></td>
                                <td class="px-3 py-2 border"><?= 'Rp ' . number_format((float)($r['subtotal'] ?? 0), 0, ',', '.'); ?></td>
                                <td class="px-3 py-2 border font-semibold"><?= 'Rp ' . number_format((float)($r['final_total'] ?? 0), 0, ',', '.'); ?></td>
                                <td class="px-3 py-2 border">
                                    <?php $sv = strtolower(trim($r['status'] ?? '')); ?>
                                    <span class="px-2 py-1 text-xs rounded
                    <?= in_array($sv, ['paid', 'approved', 'success', 'complete', 'completed']) ? 'bg-emerald-100 text-emerald-700'
                                : (in_array($sv, ['pending', 'unpaid', 'waiting']) ? 'bg-amber-100 text-amber-700'
                                    : (in_array($sv, ['cancelled', 'canceled', 'rejected', 'failed']) ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-700')); ?>">
                                        <?= esc(ucfirst($r['status'] ?? '-')); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 border"><?= !empty($r['registered_at']) ? date('d M Y H:i', strtotime($r['registered_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CLASS PROPOSALS -->
        <section>
            <h2 class="text-xl font-semibold text-stone-800 mb-3">Class Proposals</h2>
            <div class="overflow-x-auto bg-white shadow rounded-xl">
                <table class="min-w-full table-auto text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-3 py-2 border">ID</th>
                            <th class="px-3 py-2 border">User</th>
                            <th class="px-3 py-2 border">Title</th>
                            <th class="px-3 py-2 border">Schedule</th>
                            <th class="px-3 py-2 border">Location</th>
                            <th class="px-3 py-2 border">Status</th>
                            <th class="px-3 py-2 border">Active</th>
                            <th class="px-3 py-2 border">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proposals as $p): ?>
                            <tr class="border-b">
                                <td class="px-3 py-2 border"><?= esc($p['id']); ?></td>
                                <td class="px-3 py-2 border">
                                    <?= esc(($p['user_name'] ?? '') !== '' ? $p['user_name'] : '#' . $p['user_id']); ?><br>
                                    <span class="text-xs text-gray-500"><?= esc($p['user_email'] ?? ''); ?></span>
                                </td>
                                <td class="px-3 py-2 border"><?= esc($p['title']); ?></td>
                                <td class="px-3 py-2 border">
                                    <?php
                                    $d = $p['schedule_date'] ?? '';
                                    $st = $p['start_time'] ?? '';
                                    $et = $p['end_time'] ?? '';
                                    $dFmt = $d ? date('d M Y', strtotime($d)) : '-';
                                    $tFmt = ($st && $et) ? (date('H:i', strtotime($st)) . ' - ' . date('H:i', strtotime($et))) : '-';
                                    echo esc(trim($dFmt . ' • ' . $tFmt, ' •'));
                                    ?>
                                </td>
                                <td class="px-3 py-2 border"><?= esc($p['location'] ?? '-'); ?></td>
                                <td class="px-3 py-2 border"><?= esc(ucfirst($p['status'] ?? 'pending')); ?></td>
                                <td class="px-3 py-2 border">
                                    <span class="px-2 py-1 text-xs rounded <?= !empty($p['is_active']) ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700'; ?>">
                                        <?= !empty($p['is_active']) ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 border"><?= !empty($p['created_at']) ? date('d M Y H:i', strtotime($p['created_at'])) : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</div>
<?= $this->endSection(); ?>