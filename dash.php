<?php
session_start();
include("configure/found.php");
include("configure/headframe.php");

if (!isset($_SESSION['username'])) {
    header("Location: /");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT username, power, profile_img FROM players WHERE username = ?";
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
    $profileImg = $row['profile_img'];
} else {
    header("Location: /");
    exit();
}

$stmt->close();

$customProfileImg = "renders/imgs/" . $username . ".png";
if (file_exists($customProfileImg)) {
    $profileImg = $customProfileImg;
}
?>

<html>
    <link rel="stylesheet" href="Denblox.css">
    <link rel="stylesheet" href="fronted.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php
  
  include("navbar.php");
?>  
  <style>
        .badge {
            background-color: #444;
            color: #fff;
            padding: 0.2em 0.5em;
            border-radius: 1.2em;
            font-size: 0.8em;
            margin-left: 0.5em;
        }
        .profile-img {
            width: 67px;
            height: 64px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 10px;
        }
        .logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h1>
        <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="Denblox site lol" class="profile-img">
        What Good Bro, <?php echo htmlspecialchars($row['username']); ?>!
        <?php if ($power == 1000): ?>
            <span class="badge">Official Denblox Account(Owner)</span>
        <?php elseif ($power == 100): ?>
            <span class="badge">Owner</span>
        <?php elseif ($power == 4): ?>
            <span class="badge">Administrator</span>
        <?php endif; ?>
    </h1>
    <p>Hello, welcome <?php echo htmlspecialchars($row['username']); ?>. We are Almost done bc the renders are so hard this shit don't work as fuck.</p>

    <?php if ($power >= 4): ?>
        <div class="admin-panel-container">
            <div class="admin-panel">
                <a href="user/panel"><i class="fas fa-cogs"></i>Site Panel</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($power == 100): ?>
        <p>You are verified as the owner.</p>
    <?php elseif ($power == 4): ?>
        <p>You are verified as an admin.</p>
    <?php elseif ($power == 1000): ?>
        <p>You Are Verified as The Official Account Of Denblox</p>
    <?php endif; ?>

    <?php include_once("prepare.php"); ?>

    <div class="logout">
        <form method="post" action="/logout">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
