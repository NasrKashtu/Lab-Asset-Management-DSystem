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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asset Disposal</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Asset Disposal</h1>
</div>
<div class="container">
    <h2>Add Disposal Request</h2>
    <form method="post" action="">
        <label for="asset_id">Asset ID:</label>
        <input type="text" id="asset_id" name="asset_id" required>
        <label for="request_date">Request Date:</label>
        <input type="date" id="request_date" name="request_date" required>
        <label for="disposal_method">Disposal Method:</label>
        <input type="text" id="disposal_method" name="disposal_method" required>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="denied">Denied</option>
        </select>
        <input type="submit" value="Add Disposal Request">
        <?php if (isset($message)) { echo "<p style='color:green;'>$message</p>"; } ?>
    </form>

    <h2>Disposal Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Asset ID</th>
            <th>Request Date</th>
            <th>Disposal Method</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['asset_id']; ?></td>
                <td><?php echo $row['request_date']; ?></td>
                <td><?php echo $row['disposal_method']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
