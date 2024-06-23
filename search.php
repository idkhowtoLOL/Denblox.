<?php
session_start();

include("configure/found.php");
include("configure/headframe.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

// Get logged-in user's power level
$username = $_SESSION['username'];
$sql = "SELECT power FROM players WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$power = $row['power'];
$stmt->close();

// Initialize search result
$searchResults = [];

// Fetch all usernames
$sql = "SELECT id, username FROM players";
$result = $conn->query($sql);

$allUsernames = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allUsernames[] = $row;
    }
}

// Check if the search form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_query'])) {
    // Sanitize and validate the search query
    $searchQuery = htmlspecialchars($_POST['search_query']);

    // Fetch players by username
    $sql = "SELECT id, username FROM players WHERE username LIKE ?";
    $likeQuery = "%" . $searchQuery . "%";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('s', $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }

    $stmt->close();
}

// Handle ban action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ban_player'])) {
    $playerId = $_POST['player_id'];
    $banReason = htmlspecialchars($_POST['ban_reason']);

    $updateSql = "UPDATE players SET banned = 1, ban_reason = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('si', $banReason, $playerId);
    if ($stmt->execute()) {
        echo "<p>Player has been banned!</p>";
    } else {
        echo "<p>Failed to ban player.</p>";
    }
    $stmt->close();
}
?>

<?php
  
  include("navbar.php");
?>
    <title>Search Players</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <div class="container">
        <h1>Search Players</h1>
        <form method="post" class="form-inline">
            <div class="form-group">
                <label for="search_query">Username:</label>
                <input type="text" class="form-control ml-2" id="search_query" name="search_query" placeholder="Enter username" required>
            </div>
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>

        <h2 class="mt-4">All Players</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <?php if ($power >= 4): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allUsernames as $player): ?>
                    <tr>
                        <td><a href="users.php?id=<?php echo $player['id']; ?>"><?php echo htmlspecialchars($player['username']); ?></a></td>
                        <?php if ($power >= 4): ?>
                            <td>
                                <form method="post" class="form-inline">
                                    <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                                    <input type="text" class="form-control ml-2" name="ban_reason" placeholder="Ban reason" required>
                                    <button type="submit" class="btn btn-danger ml-2" name="ban_player">Ban</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (!empty($searchResults)): ?>
            <h2 class="mt-4">Search Results</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <?php if ($power >= 4): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $player): ?>
                        <tr>
                            <td><a href="users.php?id=<?php echo $player['id']; ?>"><?php echo htmlspecialchars($player['username']); ?></a></td>
                            <?php if ($power >= 4): ?>
                                <td>
                                    <form method="post" class="form-inline">
                                        <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                                        <input type="text" class="form-control ml-2" name="ban_reason" placeholder="Ban reason" required>
                                        <button type="submit" class="btn btn-danger ml-2" name="ban_player">Ban</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No players found with the username "<?php echo htmlspecialchars($searchQuery); ?>".</p>
        <?php endif; ?>
    </div>
</body>
</html>
