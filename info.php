<?php
  
  include("configure/found.php");
  
  ?>

<?php
$sql_last_user_id = "SELECT MAX(id) AS last_user_id FROM players";
$result_last_user_id = $conn->query($sql_last_user_id);
$last_user_id = $result_last_user_id->fetch_assoc()['last_user_id'];
?>





<p>The Site 3D, We Are Trying to Make More Stuff out of it, so hang on there because we are still developing the site and it will be done soon. But mostly with over <?php echo $last_user_id; ?> Members Must be Alot but we are ok with the users we have we  are still Developing stuff. if we can just be honest here.</p>