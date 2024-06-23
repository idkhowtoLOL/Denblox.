<?php
session_start();
include("configure/found.php");
include("configure/headframe.php");

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT username, power FROM players WHERE username = ?";
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
} else {
    header("Location: /");
    exit();
}

$stmt->close();
?>

    <link rel="stylesheet" href="Denblox.css">

    <div class="container">
        <h1>404 Page Not Found</h1>
        <p class="error-message">This page no longer exist, or been move to a new place! check again later <?php echo htmlspecialchars($row['username']); ?>. This Place might be in a Development rn.</p>
    </div>