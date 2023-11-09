<?php
// Include the connection file to establish a database connection
include ("connection.php");

// Get the social security number from the URL parameter
$socialsecuritynumber = $_GET["socialsecuritynumber"];

// Redirect to the welcome page if the social security number is empty
if (empty($socialsecuritynumber)) {
    header("Location: welcome.php");
    exit;
}

// Prepare and execute a database query to fetch patient information based on the social security number
$kysely = $yhteys->prepare("SELECT socialsecuritynumber, lastname, firstname, age FROM patients WHERE socialsecuritynumber = ?");
$kysely->execute(array($socialsecuritynumber));
$tulos = $kysely->fetch();

?>

<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Potilastietokanta | Potilastiedot</title>
        <link rel="icon" type="image/x-icon" href="kuvat/favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    
    <body>
        <!-- Header section with logo and page title -->
        <header class=" py-4 text-center header-fade">
            <div class="logo-container">
                <img src="kuvat/logo2.png" alt="Logo" width="100" height="100">
            </div>
            <h1 class="my-0">Potilastiedot</h1>
        </header>
        
        <!-- Main content container -->
        <div class="container mt-4">
            <div class="col-md-8">
                <?php
                // Check if patient information is fetched successfully
                if (is_array($tulos)) {
                    // Assign values to variables with proper sanitization
                    $id = isset($tulos["socialsecuritynumber"]) ? htmlspecialchars($tulos["socialsecuritynumber"]) : "";
                    $lastname = isset($tulos["lastname"]) ? htmlspecialchars($tulos["lastname"]) : "";
                    $firstname = isset($tulos["firstname"]) ? htmlspecialchars($tulos["firstname"]) : "";
                    $age = isset($tulos["age"]) ? htmlspecialchars($tulos["age"]) : "";

                    // Display patient information in a table
                    echo "<table class='table table-bordered'>";
                    echo "<thead class='thead-light'>";
                    echo "<tr>";
                    echo "<th>Potilaan henkilötunnus&nbsp;&nbsp;</th>";
                    echo "<th>Potilaan sukunimi&nbsp;&nbsp;</th>";
                    echo "<th>Potilaan etunimi&nbsp;&nbsp;</th>";
                    echo "<th>Potilaan ikä&nbsp;&nbsp;</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    echo "<td>" . $id . "</td>";
                    echo "<td>" . $lastname . "</td>";
                    echo "<td>" . $firstname . "</td>";
                    echo "<td>" . $age . "</td>";
                    echo "</tbody>";
                    echo "</table>";

                    // Display buttons for editing patient information and returning to the welcome page
                    echo '<div class="form-group">';
                    echo '<a href="" class="btn btn-primary">Muokkaa potilaan tietoja</a>';
                    echo "<td><a href=\"welcome.php\" class=\"btn btn-secondary ml-2\">Takaisin</a></td>";
                    echo '</div>';
                } else {
                    // Redirect to the welcome page if patient information is not found
                    header("Location: welcome.php");
                    exit;
                }
                ?>
            </div>
        </div>
        
        <!-- Footer section with copyright information -->
        <footer class="bg-white py-3 text-center">
            <div class="container">
                <p>&copy; 2023 Laura | All Rights Reserved</p>
            </div>
        </footer>
        
        <!-- Include JavaScript file -->
        <script src="javascript.js"></script>
    </body>
</html>