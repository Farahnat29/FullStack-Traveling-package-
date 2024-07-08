<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$server = "localhost";
$username = "root";
$password = "";
$db = "journifly";

$conn = new mysqli($server, $username, $password, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Handling POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['itemId'])) {
    $itemId = $_POST['itemId'];
    $stmt = $conn->prepare("SELECT * FROM shop WHERE id = ?");
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $insert = $conn->prepare("INSERT INTO wishlist (name, photo, price) VALUES (?, ?, ?)");
        $insert->bind_param("ssd", $item['name'], $item['photo'], $item['price']);
        $insert->execute();
        echo json_encode(["message" => "Item added to wishlist"]);
    } else {
        echo json_encode(["message" => "Item not found"]);
    }

    $stmt->close();
    $insert->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handling GET request
    $sql = "SELECT * FROM wishlist";
    $result = $conn->query($sql);
    
    $items = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        echo json_encode(["items" => $items]);  // Ensure you are wrapping items in an object under the key "items"
    } else {
        echo json_encode(["message" => "No items found in wishlist"]);
    }
    
}

$conn->close();


?>
