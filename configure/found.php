
<title>Denblox</title>
<link rel="icon" href="http://testin1.robin2.serv00.net/configure/logo/Denblox.png">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="Denblox.css">

<style>
    .announcement-banner {
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        margin: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-weight: bold;
        font-size: 1.2em;
    font-family:Montserrat, sans-serif;
  font-weight:900;
  
      transition: all 0.3s ease;
    }
</style>

<img src="configure/logo/Denblox.png" onclick="window.location.href='/dash'">

<?php
include_once("headframe.php");

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT banned FROM players WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['banned'] == 1) {
        header("Location: /banned");
        exit();
    }
    $stmt->close();
}

// Fetch announcements
$sql_announcements = "SELECT id, text FROM Announcements";
$stmt_announcements = $conn->prepare($sql_announcements);
if ($stmt_announcements === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt_announcements->execute();
$result_announcements = $stmt_announcements->get_result();
$announcements = $result_announcements->fetch_all(MYSQLI_ASSOC);
$stmt_announcements->close();
?>

<div class="container mt-5">
    <?php if (!empty($announcements)): ?>
        <div class="announcement-banner">
            <?php foreach ($announcements as $announcement): ?>
                <p><?php echo htmlspecialchars($announcement['text']); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
