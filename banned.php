<?php
include_once("configure/headframe.php");
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT banned, ban_reason FROM players WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        if ($row['banned'] > 0) {
            $ban_reason = htmlspecialchars($row['ban_reason'], ENT_QUOTES, 'UTF-8');
            ?>
     <html>
                <link rel="stylesheet" href="Denbloxban.css">
         <title>Denblox Banned</title>
       <div class="rules"> 
       
       <p>You have been banned, for violating our rules</p>
         </div>
       <div class="reason">  
                <p>Reason: <?php echo $ban_reason; ?></p>
         <div class="logo">
         <img src="configure/logo/Denblox.png">
           </div>
         </div>
            </body>
            </html>
            <?php
            exit();
        } else {
            header("Location: /dash");
            exit();
        }
    }
}
?>
