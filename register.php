<?php
//Jamie Lawler C00262499 register for new user
// Include the database connection 
include('db_connect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data and sanitize input
    $username = $_POST['username']; // Get the username from the form
    $password = $_POST['password']; // Get the password entered by the user
    $full_name = $_POST['full_name']; // Get the full name of the user
    $user_type = 'user'; // registering is only for users and not agents

    // SQL query to insert the new user into the table
    $query = "INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query); // Prepare the SQL statement
    $stmt->bind_param("sss", $username, $password, $full_name); // Bind parameters to the SQL query

    // Execute the query and check for success
    if ($stmt->execute()) {
        //  display a success message
        echo "Registration successful!";
    } else {
        //  display an error message
        echo "Error: " . $conn->error;  
    }
}
?>

<!-- HTML form for user registration -->
<link rel="stylesheet" href="styles.css"> <!-- Link to CSS -->
<form method="POST" action="register.php"> <!-- Form submission via POST method -->
    Username: <input type="text" name="username" required><br>  <!-- Username input field -->
    Password: <input type="password" name="password" required><br>  <!-- Password input field -->
    Full Name: <input type="text" name="full_name" required><br>  <!-- Full name input field -->
    <input type="submit" value="Register"><br> <!-- Submit button -->
    <p>Already registered? <a href="login.php">Login here</a></p> <!-- Link to the login page -->
</form>
