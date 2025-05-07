<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['username']; ?> (<a href="logout.php">Logout</a>)</p>
</div>
<div class="container">
    <h2>Options</h2>
    <ul>
        <?php if ($role == 'admin') { ?>
            <li><a href="asset_management.php">Asset Management</a></li>
            <li><a href="maintenance.php">Maintenance and Calibration</a></li>
            <li><a href="procurement.php">Procurement Management</a></li>
            <li><a href="disposal.php">Asset Disposal</a></li>
            <li><a href="report.php">Reporting and Analytics</a></li>
        <?php } elseif ($role == 'researcher') { ?>
            <li><a href="asset_management.php">Asset Management</a></li>
            <li><a href="procurement.php">Procurement Management</a></li>
            <li><a href="report.php">Reporting and Analytics</a></li>
        <?php } elseif ($role == 'technician') { ?>
            <li><a href="maintenance.php">Maintenance and Calibration</a></li>
            <li><a href="report.php">Reporting and Analytics</a></li>
        <?php } elseif ($role == 'student') { ?>
            <li><a href="report.php">Reporting and Analytics</a></li>
        <?php } ?>
    </ul>
</div>
</body>
</html>
