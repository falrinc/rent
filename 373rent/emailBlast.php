<?php
$username = "root";
$password = "password";
$hostname = "localhost";
$dbname = "aptinfo";
$connected = TRUE;

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    $connected = FALSE;
}

if($connected) {
    $sql = "SELECT id, name FROM aptlist WHERE curdate() >= available and available is not NULL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            $sub_query = "SELECT distinct email FROM notify WHERE apt='" . $row["id"] . "'";
            $sub_res = $conn->query($sub_query);

            if ($sub_res->num_rows > 0) {
                while($sub_row = $sub_res->fetch_assoc()) {
                    $headers = 'MIME-VERSION: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'From:  373-RENT <notify@373-rent.com>' . " \r\n" .
                        'Reply-To: notify@373-rent.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    mail($sub_row["email"], "A Property Just Became Available", "Please do not reply to this email. \r\n\r\n " . $row["name"] . " is now available to rent! Just head to <a href=\"373-rent.com\">373-rent.com</a> to request more information about this property.", $headers);
                }
            }

            $sub_query = "DELETE FROM notify WHERE apt='" . $row["id"] . "'";
            $sub_res = $conn->query($sub_query);
        }
    }
}
?>