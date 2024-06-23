<?php
session_start();
include("configure/found.php");
include("configure/headframe.php");

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT id, username, power FROM players WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $power = $row['power'];
    $player_id = $row['id'];
} else {
    header("Location: /");
    exit();
}

$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denblox - Jobs</title>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <h1>Jobs</h1>
    <p>Hello <?php echo htmlspecialchars($row['username']); ?>! Now, we are hiring! Want to get hired? Check out <a href="denbloxhire.php">Denblox Hire Positions</a>.</p>
    <h2>Check Your Application</h2>
    <?php
    // Check the status of the user's job application
    $application_sql = "SELECT id, status FROM denbloxhire WHERE id = ?";
    $stmt = $conn->prepare($application_sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('i', $player_id);
    $stmt->execute();
    $application_result = $stmt->get_result();
    $stmt->close();

    if ($application_result->num_rows > 0) {
        $application_row = $application_result->fetch_assoc();
        echo "<p>Your application status: " . $application_row['status'] . "</p>";
        echo "<p><a href='view_application.php?id=" . $application_row['id'] . "'>View Your Application</a></p>";
    } else {
        echo "<p>You haven't applied for any jobs yet.</p>";
    }
    ?>
</body>
</html>
