<?php
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

// Check if shop_id is set and not empty
if (isset($_POST['shop_id']) && !empty($_POST['shop_id'])) {
    // Retrieve shop ID from the AJAX request
    $shop_id = $_POST['shop_id'];

    // Prepare SQL statement to fetch shop information
    $sql = "SELECT name, price, photo FROM shop WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();

    // Check for SQL execution errors
    if ($stmt->error) {
        die("SQL execution error: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // Check if there is a matching shop
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Construct response data
        $response = array(
            'name' => $row['name'],
            'price' => $row['price'],
            'photo' => $row['photo'] // Include photo path in response
        );

        // Send JSON response
        echo json_encode(array('status' => 'success', 'data' => $response));
    } else {
        // If no matching shop found
        echo json_encode(array('status' => 'error', 'message' => 'Shop not found'));
    }

    // Close result set
    $result->close();
} else {
    // If shop_id is not set or empty
    echo json_encode(array('status' => 'error', 'message' => 'Invalid shop ID'));
}

// Close prepared statement and database connection
$stmt->close();
$conn->close();
?>
