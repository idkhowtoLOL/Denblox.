<?php
session_start();

include("configure/found.php");
include("configure/headframe.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$username = $_SESSION['username'];

// Update the user's online status to 1
$sql = "UPDATE players SET online_status = 1 WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->close();

// Check if the ID parameter is set
if (!isset($_GET['id'])) {
    die("No user ID specified.");
}

$playerId = intval($_GET['id']);

// Fetch the user's information
$sql = "SELECT username, power, online_status, banned FROM players WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('i', $playerId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();
$stmt->close();

$username = htmlspecialchars($user['username']);
$power = intval($user['power']);
$onlineStatus = $user['online_status'] ? 'Online' : 'Offline';
$onlineStatusClass = $user['online_status'] ? 'text-success' : 'text-secondary';
$banned = intval($user['banned']);

// Determine the user's role based on power level
if($power === 1000) {
    $role = 'Official Denblox Account (Powerful Owner)'; 
 } elseif 
  ($power === 100) {
    $role = 'Owner';
} elseif ($power === 4) {
    $role = 'Administrator';
} elseif ($power === 0) {
    $role = 'Player';
} else {
    $role = 'Unknown Role'; // Fallback in case other power levels exist
}
?>

<?php
  
  include("navbar.php");
?>
    <title><?php echo $username; ?>'s Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <div class="container">
        <?php if ($banned): ?>
            <div class="alert alert-danger" role="alert">
                This user is banned.
            </div>
        <?php endif; ?>
        <h1><?php echo $username; ?>'s Profile</h1>
        <p>Role: <?php echo $role; ?></p>
        <p>Status: <span class="<?php echo $onlineStatusClass; ?>"><?php echo $onlineStatus; ?></span></p>
    </div>
</body>
</html>
