<?php 
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim(filter_input(INPUT_POST, "name", FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $details = trim(filter_input(INPUT_POST, "details", FILTER_SANITIZE_SPECIAL_CHARS));

    if ($name == "" || $email == "" || $details == "") {
        echo "Please fill in the required fields: Name, Fields and Details";
        exit;
    }
    if ($_POST["addresss"] != "") {
        echo "Bad form input";
        exit;
    }

    require("inc/phpmailer/Exception.php");
    require("inc/phpmailer/PHPMailer.php");
    require("inc/phpmailer/SMTP.php");

    $mail = new PHPMailer(true);

    if (!$mail->ValidateAddress($email)) {
        echo "Invalid Email Address";
        exit;
    }


    $email_body = "";
    $email_body .= "Name " . $name . "\n";
    $email_body .= "Email " . $email . "\n";
    $email_body .= "Details " . $details . "\n";

    // To do: Send Email
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'user@example.com';                 // SMTP username
        $mail->Password = 'secret';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
    
        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('osedread@gmail.com', 'Jose');     // Add a recipient
        
        //Content
        $mail->isHTML(false);                                  // Set email format to HTML
        $mail->Subject = 'Personal Media Library Suggestions from ' . $name;
        $mail->Body    = $email_body;
        $mail->AltBody = $email_body;
    
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }

    header("Location:suggest.php?status=thanks");

}

$pageTitle = "Suggest a Media Item";
$section = "suggest";

include("inc/header.php"); ?>

<div class="section page">
    <h1>Suggest a Media Item</h1>
    <?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") {
        echo "<p>Thanks for the email! I&rsquo;ll check out your suggestion shortly!</p>";
    } else {
        ?>
    <p>If you think there is something I&rsquo;m missing, let me know! Complete the form to send me an email.</p>
    <form method="post" action="suggest.php">
    <table>
    <tr>
        <th><label for="name">Name</label></th>
        <td><input type="text" id="name" name="name" /></td>
    </tr>
    <tr>
        <th><label for="email">Email</label></th>
        <td><input type="text" id="email" name="email"></td>
    </tr>
    <tr>
        <th><label for="name">Suggest Item Details</label></th>
        <td><textarea name="details" id="details"></textarea></td>
    </tr>
    <tr style="display:none">
        <th><label for="address">Address</label></th>
        <td><input type="text" id="address" name="address">
        <p>Please leave this field blank.</p></td>
    </tr>
        
    </table>
    <input type="submit" value="Send" />
    </form>
    <?php } ?>
</div>

<?php include("inc/footer.php"); ?>