<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name ="wirtschaftsnachhilfe";
    $conn = "";

    try {
        $conn = mysqli_connect($db_server, $db_user, $db_password, $db_name, 3306);
        mysqli_set_charset($conn, "utf8mb4");
    }
    catch(mysqli_sql_exception $err){
        echo '
            <div style="padding: 20px;">
                <h2>Ihre Daten konnten vom Server nicht entgegengenommen werden...</h2>
                <p>Bitte versuchen Sie es später noch einmal.</p>
                <a href="../index.html">
                    <button class="page-button" style="margin-top: 0.75rem">
                        Zurück zur Startseite
                    </button>
                </a>
            </div>';
        error_log($err->getMessage()); 
    }
?>