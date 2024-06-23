<!DOCTYPE html>
<html>
<head>
    <title>Register - Denblox</title>
    <link rel="stylesheet" href="Denblox.css">
</head>
<body>
    <?php
    session_start();
    include("configure/found.php");
    include("configure/headframe.php");

    if (isset($_SESSION['username'])) {
        header("Location: /dash");
        exit();
    }
    ?>

    <p>Welcome to Denblox, Register 3D Site Rendering And Stuff, I hope you have fun!</p>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $power = 0;

        $emailCheckSql = "SELECT * FROM players WHERE LOWER(email) = LOWER(?)";
        $stmt = $conn->prepare($emailCheckSql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            header("Location: /dash");
            exit();
        } else {
            $stmt->close();

            $usernameCheckSql = "SELECT * FROM players WHERE LOWER(username) = LOWER(?)";
            $stmt = $conn->prepare($usernameCheckSql);
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                echo "<p>womp womp for you lol! this username already been taken</p>";
            } else {
                $stmt->close();

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insertSql = "INSERT INTO players (username, password, email, power) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertSql);
                if ($stmt === false) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param('sssi', $username, $hashed_password, $email, $power);
                if ($stmt->execute()) {
                    header("Location: /dash");
                    exit();
                } else {
                    die("Registration failed: " . $stmt->error);
                }
            }
            $stmt->close();
        }
    }
    ?>

    <p>If you are experiencing issues with signup, use the same password and username below. Go <a href="/login">login now</a>, buds!</p>

    <form method="post" action="">
        <div class="username">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="password">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="email">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="register">
            <button type="submit">Register</button>
        </div>
    </form>
</body>
</html>
