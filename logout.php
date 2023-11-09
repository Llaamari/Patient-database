<?php
// Start a new session or resume the existing session
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session, effectively logging the user out
session_destroy();

// Redirect the user to the login page after logout
header("location: login.php");

// Ensure no further code is executed after the header redirection
exit;
?>