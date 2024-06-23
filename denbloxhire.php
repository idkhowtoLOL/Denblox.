<?php
session_start();


if (!isset($_SESSION['username'])) {
  
    header("Location: /login.php");
    exit();
}

include("configure/found.php");
include("configure/headframe.php");


$username = $_SESSION['username'];

$sql = "SELECT email, id FROM players WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $player_id = $row['id'];
} else {
 
    die("User not found.");
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    
    $message = htmlspecialchars($_POST['message']);

 
    $insert_sql = "INSERT INTO denbloxhire (player_id, message, status) VALUES (?, ?, 'pending')";
    $insert_stmt = $conn->prepare($insert_sql);
    if ($insert_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $insert_stmt->bind_param('is', $player_id, $message);
    if ($insert_stmt->execute()) {

        header("Location: /success.php");
        exit();
    } else {
        
        die("Failed to insert message.");
    }
}

?>

<html>
<head>
    <title>Appeal Form</title>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <h1>Appeal Form</h1>
    <p>Welcome, <?php echo htmlspecialchars($username); ?>! You're email ig: <?php echo htmlspecialchars($email); ?></p>
  <div class="appealing">  
  <form method="post">
        <label for="message">Enter your appeal message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Submit</button>
    </form>
    </div>
</body>
</html>
