<?php
// Define constants for the database connection
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'potilastietokanta');

// Attempt to establish a connection to the database using the defined constants
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if($link === false) {
    // Display an error message and terminate the script if the connection fails
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>