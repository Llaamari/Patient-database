<?php
// Include the configuration file to access the database connection
require_once "config.php";

// Initialize variables for user input and error messages
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Process the form data when the form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and process the username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Ole hyvä ja kirjoita käyttäjätunnus.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Käyttäjätunnus voi sisältää vain kirjaimia, numeroita ja alaviivoja.";
    } else {
        // Check if the username is already taken
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);

            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Tämä käyttäjänimi on jo varattu.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oho! Jotain meni pieleen. Yritä myöhemmin uudelleen.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }

    // Validate and process the password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Ole hyvä ja anna salasana.";     
    } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Salasanassa on oltava vähintään 6 merkkiä.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate and process the password confirmation
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Vahvista salasana.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Salasana ei täsmää.";
        }
    }
    
    // Check for any validation errors before inserting data into the database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        // Insert user data into the database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            if(mysqli_stmt_execute($stmt)) {
                // Redirect to the login page after successful registration
                header("location: login.php");
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
        <title>Potilastietokanta | Kirjaudu</title>
        <link rel="icon" type="image/x-icon" href="kuvat/favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    
    <body>
        <!-- Main content container -->
        <div class="bottom">
            <div class="wrapper">
                <h2>Kirjaudu</h2>
                <div class="imgcontainer">
                    <img src="kuvat/rekisteröinti.jpg" alt="Uusi käyttäjä" class="avatar">
                </div>
                
                <p style="text-align: center;">Täytä tämä lomake luodaksesi tilin.</p>

                <!-- Registration form with client-side validation -->
                <form name="registrationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
                <div class="container">
                    <!-- Username input with icon and error message -->
                    <div class="form-group">
                        <label><b>Käyttäjätunnus</b></label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
                        </svg>
                        
                        <input type="text" placeholder="Syötä käyttäjätunnus" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                        <span class="invalid-feedback"><?php echo $username_err; ?></span>
                    </div>
                    
                    <!-- Password input with icon and error message -->
                    <div class="form-group">
                        <label><b>Salasana</b></label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                            <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                            <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        
                        <input type="password" placeholder="Kirjoita salasana" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    
                    <!-- Confirm password input with icon and error message -->
                    <div class="form-group">
                        <label><b>Vahvista salasana</b></label>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                        </svg>
                        
                        <input type="password" placeholder="Kirjoita salasana uudelleen" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                    </div>
                    
                    <!-- Submit and reset buttons -->
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Lähetä">
                        <input type="reset" class="btn btn-secondary ml-2" value="Nollaa">
                    </div>
                    
                    <!-- Login link for users with existing accounts -->
                    <p>Onko sinulla jo tili? <a href="login.php">Kirjaudu sisään tästä</a>.</p>
                </div>
            </form>
        </div>
    </div>   
</body>
</html>