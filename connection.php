<?php
// Attempt to create a new PDO (PHP Data Objects) database connection
try {
    $yhteys = new PDO("mysql:host=localhost;dbname=potilastietokanta", "root", "");
} catch (PDOException $e) {
    // Display an error message and terminate the script if the connection fails
    die("VIRHE: ".getMessage());
}

// Set PDO attributes for error reporting and exception handling
$yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Set the character set for the connection to Latin-1
$yhteys->exec("SET NAMES latin1");

?>