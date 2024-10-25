<?php
// Jamie lawler c00262499 submit a ticket
// Start a session
session_start();

// Include the db connection
include('db_connect.php');

// Check if the user is logged in by verifying 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to user login page if the user is not logged in
    header("Location: user_login.php");
    exit;
}

// Process the ticket submission if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and assign values from the form and session
    $user_id = $_SESSION['user_id'];  // ID of the logged-in user
    $subject = $_POST['subject'];      // Ticket subject provided by the user
    $description = $_POST['description'];  // Ticket description provided by the user
    $status = 'Open';  // Set default status to 'Open' for new tickets

    // Prepare an SQL query to insert the new ticket into the database
    $query = "INSERT INTO tickets (user_id, subject, description, status, date_submitted) VALUES (?, ?, ?, ?, NOW())";
    
    // Prepare the statement to avoid SQL injection attacks
    $stmt = $conn->prepare($query);
    
    // Bind the parameters (user_id, subject, description, and status) to the query
    $stmt->bind_param("isss", $user_id, $subject, $description, $status);

    // Execute the query and check if insertion was successful
    if ($stmt->execute()) {
        // Redirect to user dashboard upon successful ticket submission
        header("Location: user_dashboard.php");
    } else {
        // Display error message if insertion fails
        echo "Error: " . $conn->error;
    }
}
?>
