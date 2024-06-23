<?php
  
  include("configure/found.php");
  
  ?>

<?php
$sql_last_user_id = "SELECT MAX(id) AS last_user_id FROM players";
$result_last_user_id = $conn->query($sql_last_user_id);
$last_user_id = $result_last_user_id->fetch_assoc()['last_user_id'];
?>





<h1> <?php echo $last_user_id; ?> Users</h1>

<p>Recently The site been  Doing well  we wanna know if there any issue if there are please report them to us. The Renders Are being fix soon So don't wait to soon. </p>
  
