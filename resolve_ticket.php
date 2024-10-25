<?php
//Jamie Lawler C00262499 Reslove a ticket
// Start the session
session_start();

// Include the database connection
include('db_connect.php');

// Check if the agent is logged in by presence of 'agent_id' in the session
if (!isset($_SESSION['agent_id'])) {
    // Redirect to the agent login page if not logged in
    header("Location: agent_login.php");
    exit;
}

// Ensure that a 'ticket_id' is provided in the URL parameters
if (isset($_GET['ticket_id'])) {
    // Retrieve the ticket ID from the URL
    $ticket_id = $_GET['ticket_id'];

    // SQL query to update the ticket's status to "Resolved" and add resolution details
    $query = "UPDATE tickets SET status = 'Resolved', resolution_details = 'Ticket has been resolved.' WHERE ticket_id = ?";
    
    // Statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    
    // Bind the ticket ID parameter to the query
    $stmt->bind_param("i", $ticket_id);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        // Redirect back to the support dashboard with a success message
        header("Location: support_dashboard.php?message=Ticket resolved successfully");
    } else {
        // Display an error message if the query execution fails
        echo "Error: " . $conn->error;
    }
} else {
    // Display an error message if 'ticket_id' was not provided
    echo "No ticket ID provided.";
}
?>
