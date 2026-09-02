<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_id = $_POST['asset_id'];
    $request_date = $_POST['request_date'];
    $disposal_method = $_POST['disposal_method'];
    $status = $_POST['status'];

    $sql = "INSERT INTO disposal_requests (asset_id, request_date, disposal_method, status) VALUES ('$asset_id', '$request_date', '$disposal_method', '$status')";
    if ($conn->query($sql) === TRUE) {
        $message = "Disposal request added successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM disposal_requests";
$result = $conn->query($sql);

$page_title = 'Asset Disposal';
include 'includes/head.php';
$inputClass = 'w-full rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/30';
$labelClass = 'mb-1.5 block text-sm font-medium text-gray-700';
?>

<main class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8 flex items-center gap-3">
        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary"><?php echo icon('trash', 'h-6 w-6'); ?></span>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Asset Disposal</h1>
            <p class="mt-1 text-sm text-gray-500">Request and track disposal of retired assets.</p>
        </div>
    </div>

    <?php if (isset($message)) { ?>
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"><?php echo $message; ?></div>
    <?php } ?>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-1">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-900">Add Disposal Request</h2>
                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="asset_id" class="<?php echo $labelClass; ?>">Asset ID</label>
                        <input type="text" id="asset_id" name="asset_id" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="request_date" class="<?php echo $labelClass; ?>">Request Date</label>
                        <input type="date" id="request_date" name="request_date" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="disposal_method" class="<?php echo $labelClass; ?>">Disposal Method</label>
                        <input type="text" id="disposal_method" name="disposal_method" required class="<?php echo $inputClass; ?>">
                    </div>
                    <div>
                        <label for="status" class="<?php echo $labelClass; ?>">Status</label>
                        <select id="status" name="status" class="<?php echo $inputClass; ?>">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="denied">Denied</option>
                        </select>
                    </div>
                    <button type="submit" class="flex w-full items-center justify-center gap-1.5 rounded-full bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 active:scale-[0.98]"><?php echo icon('plus', 'h-4 w-4'); ?> Add Disposal Request</button>
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
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Request Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Method</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result->num_rows === 0) { echo empty_state_row(5, 'No disposal requests yet.'); } ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">#<?php echo $row['id']; ?></td>
                                <td class="px-4 py-3 font-medium text-gray-900">#<?php echo $row['asset_id']; ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['request_date']); ?></td>
                                <td class="px-4 py-3 text-gray-600"><?php echo htmlspecialchars($row['disposal_method']); ?></td>
                                <td class="px-4 py-3"><?php echo status_chip($row['status']); ?></td>
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
