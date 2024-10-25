<?php
// Jamie Lawler C00262499 user view screen
// Start a session
session_start();

// Include the db connection 
include('db_connect.php');

// Check if the user is logged in by verifying 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to the user login page if the user is not logged in
    header("Location: user_login.php");
    exit; // Stop the script after redirection
}

// Get the ticket ID from the URL
$ticket_id = $_GET['ticket_id'];

// Prepare an SQL query to fetch the ticket details based on ticket ID and user ID
$query = "SELECT * FROM tickets WHERE ticket_id = ? AND user_id = ?";
$stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
$stmt->bind_param("ii", $ticket_id, $_SESSION['user_id']); // Bind parameters (ticket_id, user_id)
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set from the executed query
$ticket = $result->fetch_assoc(); // Fetch the ticket details as an associative array

// Check if the ticket exists
if (!$ticket) {
    // If no ticket is found, display an error message
    echo "Ticket not found.";
    exit; // Stop script execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Ticket</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>
<div class="container">
    <h1>Ticket Details</h1>
    <!-- Display ticket details -->
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['subject'] ?? ""); ?></p> <!-- Ticket subject -->
    <p><strong>Description:</strong> <?php echo htmlspecialchars($ticket['description'] ?? ""); ?></p> <!-- Ticket description -->
    <p><strong>Status:</strong> <?php echo $ticket['status'] ?? ""; ?></p> <!-- Ticket status -->
    <p><strong>Date Submitted:</strong> <?php echo $ticket['date_submitted'] ?? ""; ?></p> <!--  Submission date -->
    <p><strong>Resolution Details:</strong> <?php echo htmlspecialchars($ticket['resolution_details'] ?? ""); ?></p> <!-- Resolution details -->

    <?php if ($ticket['status'] == 'Resolved') { ?>
        <!-- Show "Close Ticket" option if status is Resolved -->
        <form action="close_ticket.php" method="POST">
            <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>"> <!-- Hidden input for ticket ID -->
            <input type="submit" value="Close Ticket"> <!-- Submit button to close the ticket -->
        </form>
    <?php } elseif ($ticket['status'] == 'Elevated') { ?>
        <!-- Inform user if the ticket status is Elevated -->
        <p><em>Your ticket has been elevated for additional support. Please wait for further updates.</em></p>
    <?php } elseif ($ticket['status'] == 'Closed') { ?>
        <!-- Message for Closed tickets -->
        <p><em>This ticket is closed. No further actions are available.</em></p>
    <?php } elseif ($ticket['status'] == 'Open') { ?>
        <!-- Show "Close Ticket" option for Open tickets -->
        <form action="close_ticket.php" method="POST">
            <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>"> <!-- Hidden input for ticket ID -->
            <input type="submit" value="Close Ticket"> <!-- Submit button to close the ticket -->
        </form>
        <p><em>Your ticket is currently open and under review by a support agent.</em></p>
    <?php } ?>

    <br><a href="user_dashboard.php">Back to Dashboard</a> <!-- Link to return to the user dashboard -->
</div>
</body>
</html>
