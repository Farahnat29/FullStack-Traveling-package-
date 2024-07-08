<?php

session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phnumber1"];
    $arrivalDate = $_POST["arrival-date"];
    $departureDate = $_POST["departure-date"];
    $gender = $_POST["gender"];
    $nationality = $_POST["nationality"];
    $message = $_POST["message"];
    $receiveOffers = isset($_POST["receive_offers"]) ? $_POST["receive_offers"] : "No"; // Check if the checkbox is checked

    // Validate and sanitize data (you can add your validation here)
// Store the name value in a session variable
$_SESSION['name'] = $name;
    // Connect to your database (replace these with your actual database credentials)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "journifly";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into the database
    $sql = "INSERT INTO cars (name, email, phone, arrival_date, departure_date, gender, nationality, message, receive_offers)
    VALUES ('$name', '$email', '$phone', '$arrivalDate', '$departureDate', '$gender', '$nationality', '$message', '$receiveOffers')";

    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        // Redirect to a confirmation page
        header("Location: http://localhost/payment.html#");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
