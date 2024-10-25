<?php
session_start();  // Start the session
session_destroy();  // Stop the session to log out the user
header("Location: login.php");  // Redirect to login page after logout
?>
