<?php
// Start a new session to handle user data across requests
session_start();

// Include the database connection file
include("connection.php");

// Retrieve input data from the POST request
$socialsecuritynumber = $_POST["socialsecuritynumber"];
$lastname = $_POST["lastname"];
$firstname = $_POST["firstname"];
$age = $_POST["age"];

// Check if any of the required fields are empty
if (empty($socialsecuritynumber) || empty($lastname) || empty($firstname) || empty($age)) {
    // Display an error message and terminate the script
    echo "Täytä kaikki kentät.";
    exit;
}

// Prepare and execute a query to check if a patient with the given SSN already exists
$checkQuery = $yhteys->prepare("SELECT * FROM patients WHERE socialsecuritynumber = ?");
$checkQuery->execute(array($socialsecuritynumber));

// If a matching patient is found, display an error message and terminate the script
if ($checkQuery->rowCount() > 0) {
    echo "Potilas henkilötunnuksella $socialsecuritynumber on jo olemassa.";
    exit;
}

// Retrieve the user ID from the session
$loggedInUserID = $_SESSION['user_id'];

// Prepare and execute a query to insert a new patient into the database
$kysely = $yhteys->prepare("INSERT INTO patients(socialsecuritynumber, lastname, firstname, age, user_id) VALUES (?, ?, ?, ?, ?)");
$result = $kysely->execute(array($socialsecuritynumber, $lastname, $firstname, $age, $loggedInUserID));

// If the insertion is successful, set a session variable and redirect to the patient's page
if ($result) {
    $_SESSION['new_patient_added'] = true;
    $id = $yhteys->lastInsertId();
    header("Location: patient.php?socialsecuritynumber=$id");
} else {
    // Display an error message if the insertion fails
    echo "Tietojen lisäämisessä tapahtui virhe.";
}

?>