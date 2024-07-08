<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'journifly');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration
if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM Register WHERE email='$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows > 0) {
        echo "Error: Email already exists.";
    } else {
        $insertQuery = "INSERT INTO Register (full_name, email, password) VALUES ('$full_name', '$email', '$password')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
