<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $purchase_date = $_POST['purchase_date'];
    $calibration_status = $_POST['calibration_status'];

    $sql = "INSERT INTO assets (name, type, purchase_date, calibration_status) VALUES ('$name', '$type', '$purchase_date', '$calibration_status')";
    if ($conn->query($sql) === TRUE) {
        $message = "Asset added successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM assets";
$result = $conn->query($sql);

$page_title = 'Asset Management';
include 'includes/head.php';
$inputClass = 'w-full rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/30';
$labelClass = 'mb-1.5 block text-sm font-medium text-gray-700';
?>

<main class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8 flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary"><?php echo icon('archive-box', 'h-6 w-6'); ?></span>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Asset Management</h1>
            <p class="mt-1 text-sm text-gray-500">Register new lab assets and browse the current inventory.</p>
        </div>
    </div>

    <?php if (isset($message)) { ?>
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo $message; ?></div>
    <?php } ?>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-900">Add New Asset</h2>
                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="name" class="<?php echo $labelClass; ?>">Asset Name</label>
                        <input type="text" id="name" name="name" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="type" class="<?php echo $labelClass; ?>">Asset Type</label>
                        <input type="text" id="type" name="type" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="purchase_date" class="<?php echo $labelClass; ?>">Purchase Date</label>
                        <input type="date" id="purchase_date" name="purchase_date" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="calibration_status" class="<?php echo $labelClass; ?>">Calibration Status</label>
                        <select id="calibration_status" name="calibration_status" class="<?php echo $inputClass; ?>">
                            <option value="calibrated">Calibrated</option>
                            <option value="due">Due</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-full bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 active:scale-[0.98]"><?php echo icon('plus', 'h-4 w-4'); ?> Add Asset</button>
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
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Purchase Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Calibration</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result->num_rows === 0) { echo empty_state_row(5, 'No assets yet — add your first one.'); } ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
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
        </div>
    </div>
</main>

<?php include 'includes/foot.php'; ?>
