<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO asset_requests (user_id, request_date, status) VALUES ('$user_id', '$request_date', '$status')";
    if ($conn->query($sql) === TRUE) {
        $message = "Procurement request added successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM asset_requests";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Procurement Management</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<div class="header">
    <h1>Procurement Management</h1>
</div>
<div class="container">
    <h2>Add Procurement Request</h2>
    <form method="post" action="">
        <label for="request_date">Request Date:</label>
        <input type="date" id="request_date" name="request_date" required>
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="denied">Denied</option>
        </select>
        <input type="submit" value="Add Procurement Request">
        <?php if (isset($message)) { echo "<p style='color:green;'>$message</p>"; } ?>
    </form>

    <h2>Procurement Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Request Date</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['request_date']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
