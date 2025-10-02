<?php
// helper kecil untuk input datetime-local
function toDatetimeLocal(?string $dt): ?string
{
    if (!$dt) return null;
    return str_replace(' ', 'T', substr($dt, 0, 16));
}
?>

<?= csrf_field(); ?>

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Class</label>
        <select name="class_id" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">— Semua Kelas —</option>
            <?php foreach (($classes ?? []) as $c): ?>
                <option value="<?= esc($c['id']) ?>" <?= set_select('class_id', $c['id'], (isset($discount['class_id']) && (int)$discount['class_id'] === (int)$c['id'])) ?>>
                    <?= esc($c['name'] ?? ('Class #' . $c['id'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Code <span class="text-red-600">*</span></label>
        <input type="text" name="code" maxlength="50" required
            value="<?= old('code', $discount['code'] ?? '') ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Type <span class="text-red-600">*</span></label>
        <select name="type" required
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">— Pilih —</option>
            <option value="percentage" <?= set_select('type', 'percentage', (isset($discount['type']) && $discount['type'] == 'percentage')) ?>>percentage</option>
            <option value="fixed" <?= set_select('type', 'fixed', (isset($discount['type']) && $discount['type'] == 'fixed')) ?>>fixed</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Value <span class="text-red-600">*</span></label>
        <input type="number" step="0.01" name="value" required
            value="<?= old('value', $discount['value'] ?? '') ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <p class="mt-1 text-xs text-gray-500">percentage: 10.00 = 10% • fixed: 50000.00 = Rp50.000</p>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Min Participants</label>
        <input type="number" min="1" name="min_participants"
            value="<?= old('min_participants', $discount['min_participants'] ?? 2) ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Max Usage</label>
        <input type="number" min="0" name="max_usage"
            value="<?= old('max_usage', $discount['max_usage'] ?? '') ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
        <p class="mt-1 text-xs text-gray-500">Kosongkan untuk tanpa batas.</p>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Used Count</label>
        <input type="number" min="0" name="usage_count"
            value="<?= old('usage_count', $discount['usage_count'] ?? 0) ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Starts At</label>
        <input type="datetime-local" name="starts_at"
            value="<?= old('starts_at', isset($discount['starts_at']) ? toDatetimeLocal($discount['starts_at']) : '') ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Ends At</label>
        <input type="datetime-local" name="ends_at"
            value="<?= old('ends_at', isset($discount['ends_at']) ? toDatetimeLocal($discount['ends_at']) : '') ?>"
            class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
    </div>

    <div class="flex items-center gap-2 pt-2">
        <input id="is_active" type="checkbox" name="is_active" value="1"
            <?= set_checkbox('is_active', '1', isset($discount['is_active']) ? (int)$discount['is_active'] === 1 : true) ?>
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
        <label for="is_active" class="text-sm text-gray-700">Active</label>
    </div>
</div>

<?php if ($errors = session()->getFlashdata('errors')): ?>
    <div class="mt-4 rounded-md bg-red-50 p-4 text-sm text-red-700">
        <ul class="list-inside list-disc space-y-1">
            <?php foreach ($errors as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>