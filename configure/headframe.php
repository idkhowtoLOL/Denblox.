<?php
$servername = "mysql4.serv00.com";
$username = "m2456_denblox";
$password = "0_VC9/VC2qRr02nBnqxO!D8p7O>I3o";
$dbname = "m2456_denblox";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!function_exists('executeQuery')) {
    function executeQuery($conn, $sql, $params, $types) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            die("Execution failed: " . $stmt->error);
        }

        return $stmt->get_result();
    }
}
?>
