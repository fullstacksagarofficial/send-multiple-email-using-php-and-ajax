<?php
if (isset($_POST['email_data'])) {
	require 'class/class.phpmailer.php';
	$output = '';
	foreach ($_POST['email_data'] as $row) {
		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 587;
		$mail->SMTPSecure = "tls";
		$mail->SMTPAuth = true;
		$mail->Username = "username@gmail.com";
		$mail->Password = "password";
		$mail->From = 'username@gmail.com';		//Sets the From email address for the message
		$mail->FromName = 'Atechseva';					//Sets the From name of the message
		$mail->AddAddress($row["email"], $row["name"]);	//Adds a "To" address
		$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
		$mail->IsHTML(true);							//Sets message type to HTML
		$mail->Subject = 'Hello ' . ucwords($row["name"]) . '!'; //Sets the Subject of the message
		//An HTML or plain text message body
		$mail->Body = '
		Hello ' . ucwords($row["name"]) . ' !
		';
		$mail->AltBody = '';
		$result = $mail->Send();						//Send an Email. Return true on success or false on error
		if ($result["code"] == '400') {
			$output .= html_entity_decode($result['full_error']);
		}
	}
	if ($output == '') {
		echo 'send';
	} else {
		echo $output;
	}
}
