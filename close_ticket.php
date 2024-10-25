<?php
//Jamie Lawler C00262499 Close a ticket
// Start the session to manage user authentication
session_start();

// Include the db connection 
include('db_connect.php');

// Check if user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php"); // Redirect to user login page
    exit; // Terminate script execution
}

// Check if the form has been submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the ticket ID and the user ID from the session
    $ticket_id = $_POST['ticket_id']; // Get the ticket ID from the form
    $user_id = $_SESSION['user_id']; // Get the current user's ID from the session

    // Prepare the SQL query to update the ticket status to 'Closed'
    // Ensure the user is the owner of the ticket and that the ticket is either 'Resolved' or 'Open'
    $query = "UPDATE tickets SET status = 'Closed' WHERE ticket_id = ? AND user_id = ? AND (status = 'Resolved' OR status = 'Open')";
    $stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
    $stmt->bind_param("ii", $ticket_id, $user_id); // Bind parameters for ticket ID and user ID

    // Execute the query and check for success
    if ($stmt->execute()) {
        // If successful, redirect back to the user dashboard with a success message
        header("Location: user_dashboard.php?message=Ticket closed successfully");
        exit; // Ensure we exit after the redirect
    } else {
        // Display error message if the update fails
        echo "Error: " . $conn->error; // Output the error for debugging
    }
}
?>
