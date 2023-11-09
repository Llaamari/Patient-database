<?php
// Start a new session
session_start();

// Redirect to login page if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include the configuration file to access the database connection
require_once "config.php";

// Initialize variables for user input and error messages
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Process the form data when the form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and process the new password
    if(empty(trim($_POST["new_password"]))) {
        $new_password_err = "Anna uusi salasana.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Salasanassa on oltava vähintään 6 merkkiä.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate and process the password confirmation
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Vahvista salasana.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        
        // Check for any validation errors before updating the password in the database
        if(empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Salasana ei täsmää.";
        }
    }
    
    if(empty($new_password_err) && empty($confirm_password_err)) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if(mysqli_stmt_execute($stmt)) {
                // Destroy the session and redirect to login page after successful password update
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Oho! Jotain meni pieleen. Yritä myöhemmin uudelleen.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close the database connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Potilastietokanta | Nollaa salasana</title>
        <link rel="icon" type="image/x-icon" href="kuvat/favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    
    <body>
        <!-- Main content container -->
        <div class="bottom">
            <div class="wrapper">
                <h2>Nollaa salasana</h2>
                <div class="imgcontainer">
                    <img src="kuvat/resetointi.jpg" alt="Resetointi" class="avatar">
                </div>
                
                <p style="text-align: center;">Tee uusi salasana täyttämällä tämä lomake.</p>

                <!-- Reset password form -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="container">

                    <!-- New password input with error message -->
                    <div class="form-group">
                        <label><b>Uusi salasana</b></label>
                        <input type="password" placeholder="Syötä salasana" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                        <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
                    </div>
                    
                    <!-- Confirm password input with error message -->
                    <div class="form-group">
                        <label><b>Vahvista salasana</b></label>
                        <input type="password" placeholder="Syötä salasana uudestaan" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    
                    <!-- Submit and cancel buttons -->
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Lähetä">
                        <a class="btn btn-secondary ml-2" href="welcome.php">Peruuta</a>
                    </div>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>