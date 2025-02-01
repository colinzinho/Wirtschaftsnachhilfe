<?php
    //Database Connection Start
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name ="wirtschaftsnachhilfe";
    $conn = "";

    try {
        $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);
    }
    catch(mysqli_sql_exception){
        echo "Could not connect to database...";
    }
    //Database Connection End

    if($conn) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Get and save Form User Input
            $course = $_POST['course'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $streetname = $_POST['street'];
            $streetnumber = $_POST['streetnumber'];
            $postalcode = $_POST['postalcode'];
            $city = $_POST['city'];
            $email = $_POST['email'];
            $message = $_POST['message'];

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
                die('Binding parameters failed: ' . $query_getCourse->error);
            }

            // Fetch the result
            if ($query_getCourse->fetch()) {
                // Now $course_id holds the ID of the course with the given title
                echo "Course ID: " . $course_id;
            } else {
                echo "No course found with the title: " . $course;
            }

            // Close the Statement
            $query_getCourse->close();


            // Prepare the INSERT Statement
            $query_insert = $conn->prepare("INSERT INTO participant(Firstname, Lastname, CourseID, Street, City, Email, Message) VALUES(?, ?, ?, ?, ?, ?, ?)");

            // Bind the result to a variable
            if(!$query_insert->bind_param('ssissss', $firstname, $lastname, $course_id, $street, $city_postal_code, $email, $message)) {
                die('Binding parameters failed: ' . $query_insert->error);
            }
            
            // Execute the Query
            if($query_insert->execute()) {
                echo "Record inserted successfully!";
            } else {
                echo "Error: " . $query_insert->error;
            } 

            // Close the Statement
            $query_insert->close();

            //echo "<script>alert('Formular wurde erfolgreich gesendet!');</script>";
        }
    }
?>