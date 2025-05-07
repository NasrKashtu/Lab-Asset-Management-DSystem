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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asset Management</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Asset Management</h1>
</div>
<div class="container">
    <h2>Add New Asset</h2>
    <form method="post" action="">
        <label for="name">Asset Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="type">Asset Type:</label>
        <input type="text" id="type" name="type" required>
        <label for="purchase_date">Purchase Date:</label>
        <input type="date" id="purchase_date" name="purchase_date" required>
        <label for="calibration_status">Calibration Status:</label>
        <select id="calibration_status" name="calibration_status">
            <option value="calibrated">Calibrated</option>
            <option value="due">Due</option>
            <option value="overdue">Overdue</option>
        </select>
        <input type="submit" value="Add Asset">
        <?php if (isset($message)) { echo "<p style='color:green;'>$message</p>"; } ?>
    </form>

    <h2>Asset List</h2>
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
