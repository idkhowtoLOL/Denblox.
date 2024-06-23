<?php
session_start();
include("configure/found.php");
include("configure/headframe.php");

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

// Check if the application ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect or handle error when application ID is not provided
    header("Location: /");
    exit();
}

$application_id = $_GET['id'];

// Retrieve application details from the database
$sql = "SELECT * FROM denbloxhire WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('i', $application_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows !== 1) {
    // Application not found or multiple applications found, handle error
    header("Location: /");
    exit();
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application</title>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <h1>View Application</h1>
    <h2>Application Details</h2>
    <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
    <p><a href="jobs">Back to try to appeal my guy</a></p>
</body>
</html>
