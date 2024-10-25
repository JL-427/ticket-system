<?php
// Jamie Lawler C00262499 user dashborad
// Start a session 
session_start();

// Include the db connection 
include('db_connect.php');

// Check if the user is logged in by verifying 'user_id' in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to the user login page if the user is not logged in
    header("Location: user_login.php");
    exit; // Stop script execution after redirection
}

// Fetch user tickets from the database based on the logged-in user's ID
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM tickets WHERE user_id = ? ORDER BY date_submitted DESC"; // Query to select user tickets
$stmt = $conn->prepare($query); // Prepare the query to prevent SQL injection
$stmt->bind_param("i", $user_id); // Bind the user ID parameter
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set from the executed query
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the external CSS file for styling -->
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1> <!-- Display welcome message with username -->

    <!-- New Ticket Submission Form -->
    <h2>Submit a New Ticket</h2>
    <form action="submit_ticket.php" method="POST"> <!-- Form to submit a new ticket -->
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required><br> <!-- Input for ticket subject -->
        
        <label for="description">Description:</label>
        <textarea name="description" rows="4" required></textarea><br> <!-- Textarea for ticket description -->
        
        <input type="submit" value="Submit Ticket"> <!-- Submit button for the form -->
    </form>

    <!-- Display List of User's Tickets -->
    <h2>Your Tickets</h2>
    <table>
        <tr>
            <th>Ticket ID</th> <!-- Table header for Ticket ID -->
            <th>Subject</th> <!-- Table header for Ticket Subject -->
            <th>Status</th> <!-- Table header for Ticket Status -->
            <th>Actions</th> <!-- Table header for Actions -->
        </tr>
        <?php while ($ticket = $result->fetch_assoc()) { ?> <!-- Loop through each user's ticket -->
            <tr>
                <td><?php echo $ticket['ticket_id']; ?></td> <!-- Display ticket ID -->
                <td><?php echo htmlspecialchars($ticket['subject'] ?? ""); ?></td> <!-- Display ticket subject with HTML escaping -->
                <td><?php echo $ticket['status']; ?></td> <!-- Display ticket status -->

                <td>
                    <a href="view_ticket.php?ticket_id=<?php echo $ticket['ticket_id']; ?>">View</a> <!-- Link to view the ticket details -->
                </td>
            </tr>
        <?php } // End of the loop ?>
    </table>

    <!-- Logout Option -->
    <br><a href="logout.php">Logout</a> <!-- Link to log out -->
</div>
</body>
</html>
