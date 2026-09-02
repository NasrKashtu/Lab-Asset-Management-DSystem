<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM assets";
$result = $conn->query($sql);

$assets = [];
while ($row = $result->fetch_assoc()) {
    $assets[] = $row;
}
$counts = ['calibrated' => 0, 'due' => 0, 'overdue' => 0];
foreach ($assets as $a) {
    if (isset($counts[$a['calibration_status']])) {
        $counts[$a['calibration_status']]++;
    }
}

$page_title = 'Reporting and Analytics';
include 'includes/head.php';

$stats = [
    ['label' => 'Total Assets', 'value' => count($assets), 'color' => 'text-gray-900', 'iconBg' => 'bg-gray-100 text-gray-500', 'icon' => 'archive-box'],
    ['label' => 'Calibrated',   'value' => $counts['calibrated'], 'color' => 'text-green-600', 'iconBg' => 'bg-green-100 text-green-600', 'icon' => 'check-circle'],
    ['label' => 'Due',          'value' => $counts['due'], 'color' => 'text-amber-600', 'iconBg' => 'bg-amber-100 text-amber-600', 'icon' => 'clock'],
    ['label' => 'Overdue',      'value' => $counts['overdue'], 'color' => 'text-red-600', 'iconBg' => 'bg-red-100 text-red-600', 'icon' => 'exclamation-triangle'],
];
?>

<main class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8 flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary"><?php echo icon('chart-bar', 'h-6 w-6'); ?></span>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Reporting and Analytics</h1>
            <p class="mt-1 text-sm text-gray-500">Asset utilization and calibration compliance at a glance.</p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <?php foreach ($stats as $stat) { ?>
            <div class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?php echo $stat['iconBg']; ?>"><?php echo icon($stat['icon'], 'h-5 w-5'); ?></span>
                <div>
                    <p class="text-sm text-gray-500"><?php echo $stat['label']; ?></p>
                    <p class="text-2xl font-semibold <?php echo $stat['color']; ?>"><?php echo $stat['value']; ?></p>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Purchase Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Calibration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($assets) === 0) { echo empty_state_row(5, 'No assets to report on yet.'); } ?>
                <?php foreach ($assets as $row) { ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500">#<?php echo $row['id']; ?></td>
                        <td class="px-4 py-3 font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['type']); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['purchase_date']); ?></td>
                        <td class="px-4 py-3"><?php echo status_chip($row['calibration_status']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</main>

<?php include 'includes/foot.php'; ?>
