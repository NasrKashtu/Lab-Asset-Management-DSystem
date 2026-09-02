<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_id = $_POST['asset_id'];
    $maintenance_date = $_POST['maintenance_date'];
    $details = $_POST['details'];

    $sql = "INSERT INTO maintenance_records (asset_id, maintenance_date, details) VALUES ('$asset_id', '$maintenance_date', '$details')";
    if ($conn->query($sql) === TRUE) {
        $message = "Maintenance record added successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM maintenance_records";
$result = $conn->query($sql);

$page_title = 'Maintenance and Calibration';
include 'includes/head.php';
$inputClass = 'w-full rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/30';
$labelClass = 'mb-1.5 block text-sm font-medium text-gray-700';
?>

<main class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8 flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary"><?php echo icon('wrench-screwdriver', 'h-6 w-6'); ?></span>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Maintenance and Calibration</h1>
            <p class="mt-1 text-sm text-gray-500">Log maintenance and calibration work performed on assets.</p>
        </div>
    </div>

    <?php if (isset($message)) { ?>
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo $message; ?></div>
    <?php } ?>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-900">Add Maintenance Record</h2>
                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="asset_id" class="<?php echo $labelClass; ?>">Asset ID</label>
                        <input type="text" id="asset_id" name="asset_id" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="maintenance_date" class="<?php echo $labelClass; ?>">Maintenance Date</label>
                        <input type="date" id="maintenance_date" name="maintenance_date" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="details" class="<?php echo $labelClass; ?>">Details</label>
                        <textarea id="details" name="details" required rows="4" class="<?php echo $inputClass; ?>"></textarea>
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-full bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 active:scale-[0.98]"><?php echo icon('plus', 'h-4 w-4'); ?> Add Maintenance Record</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Asset ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result->num_rows === 0) { echo empty_state_row(4, 'No maintenance records yet.'); } ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">#<?php echo $row['id']; ?></td>
                                <td class="px-4 py-3 font-medium text-gray-900">#<?php echo $row['asset_id']; ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['maintenance_date']); ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['details']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/foot.php'; ?>
