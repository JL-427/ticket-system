<?php
// Jamie Lawler C00262499 Support dashborad
// Start the session
session_start(); 
//Include db connection 
include('db_connect.php');

// Check if the agent is logged in; redirect to login if not
if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit; // Terminate script execution
}

// Fetch open tickets from the database
$query = "SELECT * FROM tickets WHERE status = 'Open'";
$result = $conn->query($query); // Execute the query to get open tickets

// Process form submissions for resolving or elevating tickets
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'Resolve' button was pressed
    if (isset($_POST['resolve_ticket'])) {
        // Get ticket ID and resolution details from the form
        $ticket_id = $_POST['ticket_id'];
        $resolution_details = $_POST['resolution_details']; // Resolution details input

        // Update the ticket's status to "Resolved" and add resolution details
        $query = "UPDATE tickets SET status = 'Resolved', resolution_details = ? WHERE ticket_id = ?";
        $stmt = $conn->prepare($query); // Prepare the SQL statement
        $stmt->bind_param("si", $resolution_details, $ticket_id); // Bind parameters

        // Execute the query and check for success
        if ($stmt->execute()) {
            header("Location: support_dashboard.php?message=Ticket resolved successfully"); // Redirect on success
            exit; // Exit to prevent further processing
        } else {
            echo "Error: " . $conn->error; // Display error message if updating fails
        }
    } 
    // Check if the 'Elevate' button was pressed
    elseif (isset($_POST['elevate_ticket'])) {
        // Get ticket ID
        $ticket_id = $_POST['ticket_id'];

        // Update the ticket's status to "Elevated"
        $query = "UPDATE tickets SET status = 'Elevated' WHERE ticket_id = ?";
        $stmt = $conn->prepare($query); // Prepare the SQL statement
        $stmt->bind_param("i", $ticket_id); // Bind parameters

        // Execute the query and check for success
        if ($stmt->execute()) {
            header("Location: support_dashboard.php?message=Ticket elevated successfully"); // Redirect on success
            exit; // Exit to prevent further processing
        } else {
            echo "Error: " . $conn->error; // Display error message if updating fails
        }
    }
}
?>

<!-- Include CSS styles for the dashboard -->
<link rel="stylesheet" href="styles.css">
<h1>Welcome, Agent <?php echo $_SESSION['username']; ?></h1> <!-- Display agent's username -->
<h2>Open Tickets</h2>

<!-- Create a table to display open tickets -->
<table>
    <tr>
        <th>ID</th> <!-- Column for ticket ID -->
        <th>Subject</th> <!-- Column for ticket subject -->
        <th>Description</th> <!-- Column for ticket description -->
        <th>Status</th> <!-- Column for ticket status -->
        <th>Actions</th> <!-- Column for action links -->
    </tr>
    
    <!-- Loop through each ticket and display its details -->
    <?php while ($ticket = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $ticket['ticket_id']; ?></td> <!-- Display ticket ID -->
        <td><?php echo htmlspecialchars($ticket['subject'] ?? ""); ?></td> <!-- Display ticket subject -->
        <td><?php echo htmlspecialchars($ticket['description'] ?? ""); ?></td> <!-- Display ticket description -->
        <td><?php echo htmlspecialchars($ticket['status'] ?? ""); ?></td> <!-- Display ticket status -->
        <td>
            <?php if ($ticket['status'] == 'Open') { ?>
                <!-- Form for resolving or elevating the ticket -->
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>"> <!-- Hidden input for ticket ID -->
                    <textarea name="resolution_details" placeholder="Enter resolution details..." required></textarea> <!-- Textarea for resolution details -->
                    <input type="submit" name="resolve_ticket" value="Resolve"> <!-- Button to resolve the ticket -->
                    <input type="submit" name="elevate_ticket" value="Elevate" style="margin-left: 10px;"> <!-- Button to elevate the ticket -->
                </form>
            <?php } else if ($ticket['status'] == 'Elevated') { ?>
                <p>Awaiting Higher Support</p> <!-- Message if the ticket is already elevated -->
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- Logout option for the agent -->
<a href="logout.php">Logout</a>
