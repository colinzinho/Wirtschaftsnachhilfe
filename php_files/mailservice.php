<?php
    require '../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = '<SMTP server>';
        $mail->SMTPAuth = true;
        $mail->Username = '<email>';
        $mail->Password = '<password>';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('sender-email', '<sender-name>');
        $mail->addAddress('<receiver-email>', '<receiver-name>');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = 'Neue Kursanfrage';
        $mail->Body = "
            <h2 style='color: #000000;'>Eine neue Kursanfrage ist soeben eingetroffen.</h2>
            <p style='color: #000000;'>Nachfolgend die Eingaben aus dem Formular:</p>
            <table style='border: 1px solid black; border-collapse: collapse; color: #000000;'>
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Kurs</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$course."</td>
                </tr>
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Vorname</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$firstname."</td>
                </tr>
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Nachname</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$lastname."</td>
                </tr>    
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Strasse</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$streetname." ".$streetnumber."</td>
                </tr>
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Ort</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$postalcode." ".$city."</td>
                </tr>    
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Email</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$email."</td>
                </tr>    
                <tr>
                    <th style='border: 1px solid black; border-collapse: collapse; text-align: left; background-color: #EEF5FF;'>Nachricht</th>
                    <td style='border: 1px solid black; border-collapse: collapse;'>".$message."</td>
                </tr>    
            </table>";

        $mail->send();
    } catch(Exception $e) {
        // Log error message using {$mail->ErrorInfo}
    }
?>