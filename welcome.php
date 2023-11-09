<?php
// Starting a session to manage user login status
session_start();

// Redirecting to login page if user is not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Potilastietokanta | Tervetuloa</title>
        <link rel="icon" type="image/x-icon" href="kuvat/favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    
    <body>
        <!-- Header section with fading effect and logo container -->
        <header class=" py-4 text-center header-fade">
            <div class="logo-container">
                <img src="kuvat/logo.png" alt="Logo" width="100" height="100">
                <h1 class="my-0">Tervetuloa, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
            </div>
        </header>
        
        <!-- Main content container with dynamic notification and action buttons -->
        <div class="container mt-4">
            <?php
            // Displaying notification if a new patient is successfully added
            if (isset($_SESSION['new_patient_added']) && $_SESSION['new_patient_added'] === true) {
                echo '<div id="notification" class="notification">

                Uusi potilas lisätty onnistuneesti
                </div>';
                
                $_SESSION['new_patient_added'] = false;
            }
            ?>
            
            <!-- Two-column layout with buttons on the left and patient list on the right -->
            <div class="row">
                <div class="col-md-6 buttons-container">
                    <!-- Button to add a new patient with corresponding icon -->
                    <a href="new.php" class="btn btn-primary btn-block mb-3" style="width: 220px">Lisää uusi potilas &nbsp; <svg xmlns="http://www.w3.org/2000/svg" style="float:right;margin-top:5px;" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                        <!-- Path for the 'person plus' icon -->
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        <!-- Additional path for the 'person plus' icon -->
                        <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                    </svg></a>
                    
                    <!-- Button to reset password with corresponding icon -->
                    <a href="reset-password.php" class="btn btn-warning btn-block mb-3" style="width: 220px;">Nollaa salasana &nbsp; <svg xmlns="http://www.w3.org/2000/svg" style="float:right;margin-top:5px;" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <!-- Path for the 'x circle' icon -->
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <!-- Additional path for the 'x circle' icon -->
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg></a>
                    
                    <!-- Button to log out with corresponding icon -->
                    <a href="logout.php" class="btn btn-danger btn-block" style="width: 220px;">Kirjaudu ulos tililtäsi &nbsp; <svg xmlns="http://www.w3.org/2000/svg" style="float:right;margin-top:5px;" width="16" height="16" fill="currentColor" class="bi bi-person-down" viewBox="0 0 16 16">
                        <!-- Path for the 'person down' icon -->
                        <path d="M12.5 9a3.5 3.5 0 1 1 0 7 3.5 3.5 0 0 1 0-7Zm.354 5.854 1.5-1.5a.5.5 0 0 0-.708-.708l-.646.647V10.5a.5.5 0 0 0-1 0v2.793l-.646-.647a.5.5 0 0 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                        <!-- Additional path for the 'person down' icon -->
                        <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                    </svg></a>
                </div>
                
                <div class="col-md-6">
                    <h3>Potilaslista</h3>
                    <?php
                    // Including database connection
                    include("connection.php");
                    
                    // Retrieving user-specific patient data from the database
                    $loggedInUserID = $_SESSION["user_id"];
                    $kysely = $yhteys->prepare("SELECT socialsecuritynumber, lastname, firstname
                    
                    FROM patients
                    WHERE user_id = :loggedInUserID");
                    
                    $kysely->bindParam(':loggedInUserID', $loggedInUserID, PDO::PARAM_INT);
                    $kysely->execute();

                    // Displaying a table of patient information
                    echo "<table class='table table-bordered'>";
                    echo "<thead class='thead-light'>";
                    echo "<tr>";
                    echo "<th>Henkilötunnus</th>";
                    echo "<th>Sukunimi</th>";
                    echo "<th>Etunimi</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    // Fetching and displaying patient data
                    while ($rivi = $kysely->fetch()) {
                        echo "<tr>";
                        $id = $rivi["socialsecuritynumber"];
                        $lastname = htmlspecialchars($rivi["lastname"]);
                        $firstname = htmlspecialchars($rivi["firstname"]);
                        echo "<td><a href=\"patient.php?socialsecuritynumber=$id\">$id</a></td>";
                        echo "<td>$lastname</td>";
                        echo "<td>$firstname</td>";
                        echo "</tr>";
                    }
                    
                    echo "</tbody>";
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
        
        <!-- Footer section with copyright information and logo -->
        <footer class="bg-white py-3 text-center">
            <div class="container">
                <p>&copy; 2023 Laura | All Rights Reserved &nbsp; <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
                    <!-- Path for the 'person fill gear' icon -->
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm-9 8c0 1 1 1 1 1h5.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.544-3.393C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4Zm9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382l.045-.148ZM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
                </svg></p>
            </div>
        </footer>
        
        <script src="javascript.js"></script>
    </body>
</html>