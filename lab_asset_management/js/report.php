<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM assets";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reporting and Analytics</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Reporting and Analytics</h1>
</div>
<div class="container">
    <h2>Asset Report</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Purchase Date</th>
            <th>Calibration Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['purchase_date']; ?></td>
                <td><?php echo $row['calibration_status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
