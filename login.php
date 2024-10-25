<?php
//Jamie Lawler C00262499 login screen
// Start the session 
session_start();

// Include the database connection 
include('db_connect.php');

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username']; // Get the username 
    $password = $_POST['password']; // Get the password 

    // Adjust the SELECT queries to ensure both have the same number of columns
    // Allows for login of user and agent from same page
    $query = "
        SELECT user_id, username, password, 'user' AS user_type FROM users WHERE username = ?
        UNION
        SELECT agent_id AS user_id, username, password, 'agent' AS user_type FROM agents WHERE username = ?
    ";

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username); // Bind the username parameter for both queries
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result set

    // Check if any rows were returned (user found)
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch the user data

        // Directly compare passwords
        if ($password == $user['password']) { // Check if the entered password matches the stored password in db
            // Set session variables 
            $_SESSION['user_id'] = $user['user_id']; // Common user_id for both user types
            $_SESSION['username'] = $user['username']; // Store username in session

            // Redirect based on user type
            if ($user['user_type'] === 'agent') {
                $_SESSION['agent_id'] = $user['user_id']; // Optional: set agent_id to the same as user_id
                header("Location: support_dashboard.php"); // Redirect to the agent dashboard
            } else {
                header("Location: user_dashboard.php"); // Redirect to the user dashboard
            }
            exit; // Stop the script after redirection
        } else {
            echo "Invalid login credentials."; // Password does not match
        }
    } else {
        echo "No user or agent found."; // No matching user or agent in db
    }
}
?>

<!-- HTML form for user login -->
<link rel="stylesheet" href="styles.css"> <!-- Link to CSS stylesheet for styling -->
<form method="POST"> <!-- Form submission via POST method -->
    Username: <input type="text" name="username" required><br> <!-- Username input field -->
    Password: <input type="password" name="password" required><br> <!-- Password input field -->
    <input type="submit" value="Login"> <!-- Submit button -->
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p> <!-- Link to the registration page -->
