<?php
if(!isset($_POST["email"]) || !isset($_POST["name"])) {
    exit();
}

$email = $_POST["email"];
$name = $_POST["name"];
$message = "No Message";
$apt = "";
$subject = "Message Received";

if(isset($_POST["apartment"]) && !empty($_POST["apartment"])) $subject = $_POST["apartment"];
if(isset($_POST["message"]) && !empty($_POST["message"])) $message = $_POST["message"];

if($apt != "") {
    $subject = $subject . " RE: " . $apt;
    $message = "Interesed in " . $apt . "\r\n\r\n" . $message;
}

$headers = 'MIME-VERSION: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From:  ' . $name . ' <' . $email . '>' . " \r\n" .
            'Reply-To: '. $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
mail("rent@373-rent.com", $subject, $message, $headers);
?>