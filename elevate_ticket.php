<?php
// jamie lawler c00262499 elevate a ticket
// Start the session
session_start();

// Include the db connection
include('db_connect.php');

// Check if the person is logged in as an agent
if (!isset($_SESSION['agent_id'])) {
    // Redirect to agent login page if not logged in as an agent
    header("Location: agent_login.php");
    exit;
}

// Check if a ticket ID is provided in the URL to elevate a specific ticket
if (isset($_GET['ticket_id'])) {
    // Get the ticket ID from the URL
    $ticket_id = $_GET['ticket_id'];
    
    // Prepare the SQL query to update the ticket's status to "Elevated"
    $query = "UPDATE tickets SET status = 'Elevated' WHERE ticket_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ticket_id);

    // Execute the query and check if the update was successful
    if ($stmt->execute()) {
        // Redirect to the support dashboard upon successful elevation
        header("Location: support_dashboard.php");
    } else {
        // Display an error message if the query fails
        echo "Error: " . $conn->error;
    }
} else {
    // Redirect to the support dashboard if no ticket ID is provided
    header("Location: support_dashboard.php");
}
?>
