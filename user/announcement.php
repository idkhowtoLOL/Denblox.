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
        header("Location: /dash");
        exit();
    }
} else {
    header("Location: /");
    exit();
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['announcement_text'])) {
    $announcement_text = htmlspecialchars($_POST['announcement_text']);

    // Delete existing announcements
    $deleteSql = "DELETE FROM Announcements";
    if ($conn->query($deleteSql) === false) {
        die("Delete failed: " . $conn->error);
    }

    // Insert the new announcement
    $insertSql = "INSERT INTO Announcements (text) VALUES (?)";
    $stmt = $conn->prepare($insertSql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('s', $announcement_text);
    if ($stmt->execute()) {
        echo "<p>Announcement added successfully!</p>";
    } else {
        echo "<p>Failed to add announcement.</p>";
    }
    $stmt->close();
}

$sql = "SELECT id, text FROM Announcements";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Announcements</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Text</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["text"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No announcements found.</p>";
}
?>
<html>
<head>
    <link rel="stylesheet" href="/Denblox.css">
</head>
<body>
    <h1>Manage Announcements</h1>
    <form method="post">
        <label for="announcement_text">Enter your announcement:</label><br>
        <textarea id="announcement_text" name="announcement_text" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
