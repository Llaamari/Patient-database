<!DOCTYPE html>
<html lang="fi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Potilastietokanta | Lisää potilas</title>
        <link rel="icon" type="image/x-icon" href="kuvat/favicon.ico">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- JavaScript function to validate age input -->
        <script>
        function validateAge() {
            const ageInput = document.getElementById('age');
            const ageValue = ageInput.value;
            
            // Check if age is a valid number
            if (isNaN(ageValue) || ageValue === '') {
                alert('Ikä on annettava numerona.');
                ageInput.focus();
                return false;
            }
            
            return true;
        }
        </script>
    </head>
    
    <body>
        <div class="bottom">
            <div class="wrapper">
                <h2>Potilaan lisäys</h2>
                <div class="imgcontainer">
                    <img src="kuvat/uusi.jpg" alt="Uusi potilas" class="avatar">
                </div>
                
                <p style="text-align: center;">Ole hyvä ja täytä potilaan tiedot lisätäksesi hänet tietoihin.</p>
                <?php
                // Display success message if set
                if(isset($success_message)) {
                    ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                    <?php
                }
                ?>
                
                <!-- Form for adding a new patient -->
                <form action="add.php" method="post" onsubmit="return validateAge();">
                <div class="container">
                    <!-- Input fields for social security number, last name, first name, and age -->
                    <div class="form-group">
                        <label><b>Henkilötunnus</b></label>
                        <input type="text" placeholder="Syötä henkilötunnus" name="socialsecuritynumber" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label><b>Sukunimi</b></label>
                        <input type="text" placeholder="Syötä sukunimi" name="lastname" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label><b>Etunimi</b></label>
                        <input type="text" placeholder="Syötä etunimi" name="firstname" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label><b>Ikä</b></label>
                        <input type="text" placeholder="Syötä ikä" name="age" id="age" class="form-control" required>
                    </div>
                    
                    <!-- Buttons for submitting, resetting, and canceling the form -->
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Lisää potilas">
                        <input type="reset" class="btn btn-warning ml-1" value="Nollaa">
                        <a class="btn btn-secondary ml-1" href="welcome.php">Peruuta</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>