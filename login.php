<html>
  
  <?php
  
  include("navbar.php");
?>

    <link rel="stylesheet" href="Denblox.css">
    <?php include("configure/found.php"); ?>
    <?php include("configure/headframe.php"); ?>

    <?php
    session_start();
    // Check if the user is already logged in
    if (isset($_SESSION['username'])) {
        header("Location: /dash");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username, password FROM players WHERE LOWER(username) = LOWER(?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                
                // Start session and set username
                $_SESSION['username'] = $row['username'];
                header("Location: dash");
                exit();
            }
        }
        echo "<p>Invalid username or password.</p>";
        $stmt->close();
    }
    ?>

    <form method="post" action="">
        <div class="username">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="password">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="register">
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
