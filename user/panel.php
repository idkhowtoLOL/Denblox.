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
    if ($power < 4) {
        header("Location: /");
        exit();
    } elseif ($power >= 100) {
        echo "<form method='post' action='/user/announcement'>";
  echo "<button type='submit' name='Announce_lol'>Update Announce Dude!</button>";
        echo "</form>";
        echo "<form method='post' action='/user/check-job'>";
        echo "<button type='submit' name='hire_people'>Hire People</button>";
        echo "</form>";
    }
} else {
    header("Location: /");
    exit();
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ban_player'])) {
    $player_id = $_POST['player_id'];
    $ban_reason = $_POST['ban_reason'];

    $updateSql = "UPDATE players SET banned = 1, ban_reason = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('si', $ban_reason, $player_id);
    if ($stmt->execute()) {
        echo "<p>Player has been banned!</p>";
    } else {
        echo "<p>Failed to ban player.</p>";
    }
    $stmt->close();
}

$sql = "SELECT id, username FROM players";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Players</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='player_id' value='" . $row["id"] . "'>";
        echo "<input type='text' name='ban_reason' placeholder='Ban Reason'>";
        echo "<button type='submit' name='ban_player'>Ban</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No players found.</p>";
}
?>
<link rel="stylesheet" href="/Denblox.css">