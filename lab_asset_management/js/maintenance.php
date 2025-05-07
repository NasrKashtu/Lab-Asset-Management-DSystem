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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maintenance and Calibration</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Maintenance and Calibration</h1>
</div>
<div class="container">
    <h2>Add Maintenance Record</h2>
    <form method="post" action="">
        <label for="asset_id">Asset ID:</label>
        <input type="text" id="asset_id" name="asset_id" required>
        <label for="maintenance_date">Maintenance Date:</label>
        <input type="date" id="maintenance_date" name="maintenance_date" required>
        <label for="details">Details:</label>
        <textarea id="details" name="details" required></textarea>
        <input type="submit" value="Add Maintenance Record">
        <?php if (isset($message)) { echo "<p style='color:green;'>$message</p>"; } ?>
    </form>

    <h2>Maintenance Records</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Asset ID</th>
            <th>Maintenance Date</th>
            <th>Details</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['asset_id']; ?></td>
                <td><?php echo $row['maintenance_date']; ?></td>
                <td><?php echo $row['details']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
