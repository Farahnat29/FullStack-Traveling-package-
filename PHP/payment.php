<?php
session_start();

// Retrieve the name value from the session variable
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $cartName = $_POST['cartName'];
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cardType = $_POST['card_type'];
    $conEmail = $_POST['conEmail'];
    $agreeTerms = isset($_POST['agree_terms']) ? $_POST['agree_terms'] : 0; // Default to 0 if not set

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

    // Insert data into the payment table
    $sql_payment = "INSERT INTO payment (name, cartName, card_number, expiry_date, card_type, conEmail, agree_terms) 
            VALUES ('$name', '$cartName', '$cardNumber', '$expiryDate', '$cardType', '$conEmail', '$agreeTerms')";

    // Execute the payment query
    if ($conn->query($sql_payment) === TRUE) {
        // Now, we need to fetch personal information from the cars table
        $sql_personal = "SELECT * FROM cars WHERE name='$name'";
        $result_personal = $conn->query($sql_personal);

        // Check if personal information exists
        if ($result_personal->num_rows > 0) {
            // Fetch personal information
            $row_personal = $result_personal->fetch_assoc();
            $email = $row_personal["email"];
            $phone = $row_personal["phone"];
            $arrivalDate = $row_personal["arrival_date"];
            $departureDate = $row_personal["departure_date"];
            $gender = $row_personal["gender"];
            $nationality = $row_personal["nationality"];
            $message = $row_personal["message"];
            $receiveOffers = $row_personal["receive_offers"];
            
            // Insert into confirmation table
            $sql_confirmation = "INSERT INTO confirmation (name, email, phone, arrival_date, departure_date, gender, nationality, message, receive_offers, cartName, card_number, expiry_date, card_type, conEmail, agree_terms) 
                VALUES ('$name', '$email', '$phone', '$arrivalDate', '$departureDate', '$gender', '$nationality', '$message', '$receiveOffers', '$cartName', '$cardNumber', '$expiryDate', '$cardType', '$conEmail', '$agreeTerms')";

            // Execute the confirmation query
            if ($conn->query($sql_confirmation) === TRUE) {
                echo "Confirmation recorded successfully";
                
                // Redirect to confirmation page
                header("Location: http://localhost/confirmation.html");
                exit;
            } else {
                echo "Error: " . $sql_confirmation . "<br>" . $conn->error;
            }
        } else {
            echo "Error: Personal information not found";
        }
    } else {
        echo "Error: " . $sql_payment . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
