<?php
    error_reporting(0); // Disable all error reporting
    ini_set('display_errors', 0); // Don't display errors
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo '<!DOCTYPE html>';
        echo '<html lang="de">';
        echo '<head>';
        echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
        echo '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        echo '<meta name="author" content="Colinzinho" />';
        echo '<meta name="description" content="Formulareingang Bestätigungsseite für Schulungen & Unterricht in Wirtschaft für Silvia von Burg.">';
        echo '<title>Formulareingang Bestätigungsseite</title>';
        echo '<link rel="icon" href="img/favicon.svg" sizes="any" type="image/svg+xml">';
        echo '<link rel=”mask-icon” href=”./img/favicon.svg” color=”#000000">';
        echo '<link href="css/style.css" rel="stylesheet" />';
        echo '</head>';
        echo '<body>';
        require_once 'db_con.php';   
        //Get and save Form User Input
        $course = isset($_POST['course']) ? filter_var($_POST['course'], FILTER_SANITIZE_STRING) : '';
        $firstname = isset($_POST['firstname']) ? filter_var($_POST['firstname'], FILTER_SANITIZE_STRING) : '';
        $lastname = isset($_POST['lastname']) ? filter_var($_POST['lastname'], FILTER_SANITIZE_STRING) : '';
        $streetname = isset($_POST['street']) ? filter_var($_POST['street'], FILTER_SANITIZE_STRING) : '';
        $streetnumber = isset($_POST['streetnumber']) ? filter_var($_POST['streetnumber'], FILTER_SANITIZE_STRING) : '';
        $postalcode = isset($_POST['postalcode']) ? filter_var($_POST['postalcode'], FILTER_SANITIZE_STRING) : '';
        $city = isset($_POST['city']) ? filter_var($_POST['city'], FILTER_SANITIZE_STRING) : '';
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
        $policy = isset($_POST['policy']) ? $_POST['policy'] : '';

        if ($course == "Wählen Sie ein Angebot aus") {
            $errors['course'] = "Sie müssen einen Kurs aus der Liste auswählen.";
        }
    
        // Validate firstname and lastname (letters only, not empty or whitespace)
        foreach (['firstname', 'lastname'] as $field) {
            if (empty(trim($field))) {
                $errors[$field] = "Die Eingabe darf nicht leer sein.";
            } elseif (!preg_match("/^[a-zA-Z]+$/", $field)) {
                $errors[$field] = "Zahlen sind hier nicht erlaubt!";
            }
        }
    
        // Validate street (not empty, no whitespace, and letters only)
        if (empty(trim($streetname))) {
            $errors['street'] = "Die Eingabe darf nicht leer sein.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $streetname)) {
            $errors['street'] = "Zahlen sind hier nicht erlaubt!";
        }

        // Validate streetnumber (not empty, starts with a number)
        if (empty(trim($streetnumber))) {
            $errors['streetnumber'] = "Die Eingabe darf nicht leer sein.";
        } elseif (!preg_match("/^[0-9]/", $streetnumber)) {
            $errors['streetnumber'] = "Kein Buchstabe am Anfang.";
        }
    
        // Validate city (not empty, no whitespace, and letters only)
        if (empty(trim($city))) {
            $errors['city'] = "Die Eingabe darf nicht leer sein.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $city)) {
            $errors['city'] = "Zahlen sind hier nicht erlaubt!";
        }
    
        // Validate postal code (not empty, only numbers allowed)
        if (empty(trim($postalcode))) {
            $errors['postal_code'] = "Die Eingabe darf nicht leer sein.";
        } elseif (!preg_match("/^[0-9]+$/", $postalcode)) {
            $errors['postal_code'] = "Nur Zahlen erlaubt.";
        }
    
        // Validate email (not empty, valid email format)
        if (empty(trim($email))) {
            $errors['email'] = "Die Eingabe darf nicht leer sein.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Ungültiges E-Mail Format.";
        }
    
        // Validate policy (checkbox must be checked)
        if (!isset($policy) || $policy != 'on') {
            $errors['policy'] = "Lesen und akzeptieren Sie die Datenschutzrichtlinien.";
        }
    
        // If there are any errors, handle them
        if (empty($errors)) {
            // Proceed with processing the form (e.g., saving to database, etc.)
            // Concatenate variables for easy Persistance
            $street = $streetname . " " . $streetnumber;
            $city_postal_code = $postalcode . " " . $city;

            // Prepare the SELECT Statement - keyword prepared statements - get ID to relating Course Name
            $query_getCourse = $conn->prepare("SELECT ID FROM course WHERE Title = ?");
            
            // Bind the parameter (the 's' indicates that the parameter is a string)
            $query_getCourse->bind_param('s', $course);
            
            // Execute the Query
            $query_getCourse->execute(); 

            // Bind the result to a variable
            if(!$query_getCourse->bind_result($course_id)) {
                echo '
                <div style="padding: 20px;">
                    <h2>Ihre Eingaben konnten nicht verarbeitet werden...</h2>
                    <p>Bitte versuchen Sie es später noch einmal.</p>
                    <a href="../index.html">
                        <button class="page-button" style="margin-top: 0.75rem">
                            Zurück zur Startseite
                        </button>
                    </a>
                </div>';
                $err = 'bind_result course query';
                error_log($err->getMessage());
            }

            // Fetch the result
            if (!($query_getCourse->fetch())) {
                echo '
                <div style="padding: 20px;">
                    <h2>Das von Ihnen ausgewählte Angebot wurde nicht gefunden... </h2>';
                echo '<p>Ihr ausgewähltes Angebot: ' . $course . '</p>
                    <a href="../index.html">
                        <button class="page-button" style="margin-top: 0.75rem">
                            Zurück zur Startseite
                        </button>
                    </a>
                </div>';
                $err = 'fetch course';
                error_log($err->getMessage());
            }

            // Close the Statement
            $query_getCourse->close();


            // Prepare the INSERT Statement
            $query_insert = $conn->prepare("INSERT INTO participant(Firstname, Lastname, CourseID, Street, City, Email, Message) VALUES(?, ?, ?, ?, ?, ?, ?)");

            // Bind the result to a variable
            if(!$query_insert->bind_param('ssissss', $firstname, $lastname, $course_id, $street, $city_postal_code, $email, $message)) {
                echo '
                <div style="padding: 20px;">
                    <h2>Ihre Eingaben könnten nicht verarbeitet werden...</h2>
                    <p>Bitte versuchen Sie es später noch einmal.</p>
                    <a href="../index.html">
                        <button class="page-button" style="margin-top: 0.75rem">
                            Zurück zur Startseite
                        </button>
                    </a>
                </div>';
                $err = 'bind_param insert';
                error_log($err->getMessage());
            }
            
            // Execute the Query
            if($query_insert->execute()) {
                    echo '
                        <div style="padding: 20px;">
                            <h2>Ihr Formular wurde erfolgreich übermittelt.</h2>
                            <p>Vielen Dank für Ihre Anfrage. Ich werde mich so rasch als möglich bei Ihnen melden.</p>
                            <p>Freundliche Grüsse Silvia von Burg</p>
                            <a href="../index.html">
                                <button class="page-button" style="margin-top: 0.75rem">
                                    Zurück zur Startseite
                                </button>
                            </a>
                        </div>';
            } else {
                echo '
                <div style="padding: 20px;">
                    <h2>Der Server hat mit einem Fehler geantwortet...</h2>
                    <p>Bitte versuchen Sie es später noch einmal.</p>
                    <a href="../index.html">
                        <button class="page-button" style="margin-top: 0.75rem">
                            Zurück zur Startseite
                        </button>
                    </a>
                </div>';
            }
            // Close the Statement
            $query_insert->close();
            echo '</body>';
            echo '</html>';
        } else {
            // Display the errors
            echo '
                <div style="padding: 20px;">
                    <h2>Der Server hat mit einem Fehler geantwortet...</h2>';
            foreach ($errors as $field => $message) {
                $fieldname = ucfirst($field);
                echo "<p>$fieldname - $message</p>";
            }
            echo '<a href="../index.html">
                        <button class="page-button" style="margin-top: 0.75rem">
                            Zurück zur Startseite
                        </button>
                    </a>
                </div>';
        }
    }
?>