<?php
include("configure/headframe.php");

session_start();
if (isset($_SESSION['username'])) {
    header("Location: /dash");
    exit();
}

$sql_last_user_id = "SELECT MAX(id) AS last_user_id FROM players";
$result_last_user_id = $conn->query($sql_last_user_id);
$last_user_id = $result_last_user_id->fetch_assoc()['last_user_id'];
?>


  <meta
            name="description"
            content="Denblox, is a 3D Site Building Site, Inspired by Vextoria!"/>
<?php
  
  include("navbar.php");
?>
<div class="member">
    <h1>Welcome Denbloxians, we have over <?php echo $last_user_id; ?> users! it's a 3D Platform </h1>
    <button onclick="window.location.href='/register'">Register</button>
    <button onclick="window.location.href='/login'">Login</button>
</div>

<link rel="stylesheet" href="start.css">
<div class="denbloximg">
    <img src="configure/logo/Denblox.png" onclick="window.location.href='/search'">
</div>
