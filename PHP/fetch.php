<?php
header('Content-Type: application/json');
// Database connection parameters
$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";


// Create connection
$conn = new mysqli($server, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM wishlist";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $items = array();
    while($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
} else {
    echo json_encode(array("message" => "No items found in wishlist"));
}
$conn->close();
?>
