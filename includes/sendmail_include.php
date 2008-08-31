<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("INIT_CMS_OK")) { header("Location:../index.php"); exit; }

function sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="") {

	global $settings, $locale;
	
	require_once PATH_INCLUDES."phpmailer_include.php";
	
	$mail = new PHPMailer();
	if (file_exists(PATH_INCLUDES."languages/phpmailer.lang-".$settings['locale_code'].".php")) {
		$mail->SetLanguage($settings['locale_code'], PATH_INCLUDES."language/");
	} else {
		$mail->SetLanguage("en", PATH_INCLUDES."language/");
	}

	if ($settings['smtp_host']=="") {
		$mail->IsMAIL();
	} else {
		$mail->IsSMTP();
		$mail->Host = $settings['smtp_host'];
		$mail->Username = $settings['smtp_username'];
		$mail->Password = $settings['smtp_password'];
		if ($settings['smtp_username'] != "" && $settings['smtp_password'] != "")
			$mail->SMTPAuth = true;
	}
	
	$mail->CharSet = $settings['charset'];
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	$mail->AddReplyTo($fromemail, $fromname);
	if ($cc) { 
		$cc = explode(", ", $cc);
		foreach ($cc as $ccaddress) {
			$mail->AddCC($ccaddress);
		}
	}
	if ($bcc) {
		$bcc = explode(", ", $bcc);
		foreach ($bcc as $bccaddress) {
			$mail->AddBCC($bccaddress);
		}
	}
	if ($type == "plain") {
		$mail->IsHTML(false);
	} else {
		$mail->IsHTML(true);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if(!$mail->Send()) {
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return $mail->ErrorInfo;
	} else {
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return true;
	}

}
?>
