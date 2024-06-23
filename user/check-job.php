<?php
session_start();
include("../configure/found.php");
include("../configure/headframe.php");

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT power FROM players WHERE username = ?";
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
    if ($power < 100) {
        header("Location: /");
        exit();
    }
} else {
    header("Location: /");
    exit();
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    $updateSql = "UPDATE denbloxhire SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('si', $status, $application_id);
    if ($stmt->execute()) {
        echo "<p>Application status updated!</p>";
        if ($status == "accepted") {
            // Update user's power to 4
            $updatePowerSql = "UPDATE players SET power = 4 WHERE id = (SELECT player_id FROM denbloxhire WHERE id = ?)";
            $stmtPower = $conn->prepare($updatePowerSql);
            if ($stmtPower === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmtPower->bind_param('i', $application_id);
            if ($stmtPower->execute()) {
                echo "<p>User's power updated to 4.</p>";
            } else {
                echo "<p>Failed to update user's power.</p>";
            }
            $stmtPower->close();
        }
    } else {
        echo "<p>Failed to update application status.</p>";
    }
    $stmt->close();
}

$sql = "SELECT id, username FROM players";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Players</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No players found.</p>";
}

$sql = "SELECT id, message, status FROM denbloxhire";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Job Applications</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Message</th><th>Status</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["message"] . "</td>";
        echo "<td>" . $row["status"] . "</td>";
        echo "<td>";
        if ($row["status"] == "pending") {
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='application_id' value='" . $row["id"] . "'>";
            echo "<select name='status'>";
            echo "<option value='accepted'>Accepted</option>";
            echo "<option value='failed'>Failed</option>";
            echo "<option value='pending' selected>Pending</option>";
            echo "</select>";
            echo "<button type='submit' name='update_status'>Update Status</button>";
            echo "</form>";
        } else {
            echo "N/A";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No job applications found.</p>";
}
?>
<link rel="stylesheet" href="/Denblox.css">
