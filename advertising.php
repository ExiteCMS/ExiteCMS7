<?php
/*---------------------------------------------------+
| ExiteCMS Content Management System                 |
+----------------------------------------------------+
| Copyright 2007 Harro "WanWizard" Verton, Exite BV  |
| for support, please visit http://exitecms.exite.eu |
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the |
| GNU General Public License. For details refer to   |
| the included gpl.txt file or visit http://gnu.org  |
+----------------------------------------------------*/
require_once dirname(__FILE__)."/includes/core_functions.php";
require_once PATH_ROOT."/includes/theme_functions.php";

// temp storage for template variables
$variables = array();

// include the locale for this module
include PATH_LOCALE.LOCALESET."admin/adverts.php";

// include the sendmail module
include PATH_INCLUDES."sendmail_include.php";

// initialize the flags used in the template
$is_client = false;
$is_updated = false;
$errormessage = "";

if (iMEMBER) {
	// check if this member is a client
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id = '".$userdata['user_id']."' AND user_sponsor = '1'");
	if (dbrows($result) != 0) {
		$is_client = true;
		$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_userid = '".$userdata['user_id']."' LIMIT 1");
		if (dbrows($result) != 0) {
			// update advertisement URL
			if (isset($_POST['change'])) {
				$adverts_url = $_POST['adverts_url'];
				$result = dbquery("UPDATE ".$db_prefix."adverts SET adverts_url = '".$adverts_url."' WHERE adverts_id = '".$id."'");
				$is_updated = true;
			}
			// email statistics
			if (isset($_POST['email'])) {
				// initialize PHPmailer
				require_once PATH_INCLUDES."phpmailer_include.php";
				$mail = new PHPMailer();
				if (file_exists(PATH_INCLUDES."languages/phpmailer.lang-".$locale['phpmailer'].".php")) {
					$mail->SetLanguage($locale['phpmailer'], PATH_INCLUDES."language/");
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
				$mail->CharSet = $locale['charset'];
				$mail->From = $settings['siteemail'];
				$mail->FromName = $settings['siteusername'];
				$mail->AddAddress($userdata['user_email'], $userdata['user_name']);
				$mail->AddReplyTo($settings['siteemail'], $settings['siteusername']);
				$mail->IsHTML($userdata['user_newsletters'] != 2);

				// Build the message body
				if ($id == "all") {
					$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_userid = '".$userdata['user_id']."' ORDER BY adverts_contract_start");
					$subject = $locale['510'];
				} else {
					$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_userid = '".$userdata['user_id']."' AND adverts_id = '$id'");
					$subject = sprintf($locale['511'], $id);
				}
				// get the number of priority codes used.
				$result2 = dbquery("SELECT DISTINCT adverts_priority FROM ".$db_prefix."adverts");
				$prio_count = dbrows($result2);
				$html_body = "<font face='sans_serif'>".sprintf($locale['512'],showdate('longdate', time()))."<br><br>";
				$text_body = sprintf($locale['512'],showdate('longdate', time()))."\r\n";
				if (dbrows($result)) {
					while ($data = dbarray($result)) {
						// open table
						$html_body .= "<table width='500' cellspacing='0' cellpadding='0' border='0'>\r\n";
						$text_body .= str_repeat("-",80)."\r\n";
						// advert ID
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['460']."</td><td>: ".$data['adverts_id']."</td></tr>\r\n";
						$text_body .= str_pad($locale['460'],25).": ".$data['adverts_id']."\r\n";
						// advert location
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['462']."</td><td>: ".$ad_locations[$data['adverts_location']]."</td></tr>\r\n";
						$text_body .= str_pad($locale['462'],25).": ".$ad_locations[$data['adverts_location']]."\r\n";
						// only report priority if it's being used
						if ($prio_count > 1) {
							$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['424']."</td><td>: ".$data['adverts_priority']."</td></tr>\r\n";
							$text_body .= str_pad($locale['424'],25).": ".$data['adverts_priority']."\r\n";
						}
						// contract type
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['411']."</td><td>: ".$contract_types[$data['adverts_contract']]."</td></tr>\r\n";
						$text_body .= str_pad($locale['411'],25).": ".$contract_types[$data['adverts_contract']]."\r\n";
						switch ($data['adverts_contract']) {
							case 0:
								break;
							case 1:
								$html_body .= "<tr><td width='1%' style='white-space:nowrap'></td><td>: ".$locale['412'].": ".showdate('shortdate',$data['adverts_contract_start']);
								$text_body .= "&nbsp;- ".str_pad($locale['412'],23).": ".showdate('shortdate',$data['adverts_contract_start'])."\r\n";
								if ($data['adverts_contract_end']) {
									$html_body .= "<tr><td width='1%' style='white-space:nowrap'></td><td>: ".$locale['413'].": ".showdate('shortdate',$data['adverts_contract_end']);
									$text_body .= "&nbsp;- ".str_pad($locale['413'],23).": ".showdate('shortdate',$data['adverts_contract_end'])."\r\n";
								}
								break;
							case 2:
								$html_body .= "<tr><td width='1%' style='white-space:nowrap'></td><td>: ".$locale['414'].": ".$data['adverts_sold'];
								$text_body .= "- ".str_pad($locale['414'],23).": ".$data['adverts_sold']."\r\n";
								$html_body .= "<tr><td width='1%' style='white-space:nowrap'></td><td>: ".$locale['413'].": ".$data['adverts_sold']-$data['adverts_shown'];
								$text_body .= "- ".str_pad($locale['413'],23).": ".$data['adverts_sold']-$data['adverts_shown']."\r\n";
								break;
						}
						
						// number of advert display requests
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['479']."</td><td>: ".$data['adverts_shown']."</td></tr>\r\n";
						$text_body .= str_pad($locale['479'],25).": ".$data['adverts_shown']."\r\n";
						// number of user clicks
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['464']."</td><td>: ".$data['adverts_clicks']."</td></tr>\r\n";
						$text_body .= str_pad($locale['464'],25).": ".$data['adverts_clicks']."\r\n";
						// precentage clicks
						$percent = $data['adverts_shown'] == 0 ? 0 : substr(100 * $data['adverts_clicks'] / $data['adverts_shown'], 0, 5);
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['465']."</td><td>: ".$percent." %</td></tr>\r\n";
						$text_body .= str_pad($locale['465'],25).": ".$percent." %\r\n";
						// advert image displayed
						$ad_image = substr($data['adverts_image'],0,strlen($userdata['user_name'])) == $userdata['user_name'] ? substr($data['adverts_image'],strlen($userdata['user_name'])+1) : $data['adverts_image'];
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['417']."</td><td>: ".$ad_image."</td></tr>\r\n";
						$text_body .= str_pad($locale['417'],25).": ".$ad_image."\r\n";
						// advert URL link
						$html_body .= "<tr><td width='1%' style='white-space:nowrap'>".$locale['418']."</td><td>: ".$data['adverts_url']."</td></tr>\r\n";
						$text_body .= str_pad($locale['418'],25).": ".$data['adverts_url']."\r\n";
						// end of table
						$html_body .= "</table><br><hr><br>";
						$text_body .= str_repeat("-",80)."\r\n\r\n";
					}
					$mail->Subject = $subject;
					if ($userdata['user_newsletters'] == 2) {
					$mail->Body = $text_body;
					} else {
						$mail->Body = $html_body;
						$mail->AltBody = $text_body;
					}
					if(!$mail->Send()) {
						$error = $mail->ErrorInfo;
						$mail->ClearAllRecipients();
						$mail->ClearReplyTos();
					} else {
						$error = "";
						$mail->ClearAllRecipients(); 
						$mail->ClearReplyTos();
					}
					if ($error != "") {
						$errormessage = $error;
					} elseif ($id == "all") {
						$errormessage = $locale['953'];
					} else {
						$errormessage = sprintf($locale['952'], $id);
					}
				} else {
					opentable($locale['500']);
					echo "<div align='center'>\n<br /><b>".$locale['901']."</b><br /><br />\n</div>\n";
					closetable();
				}
			}
			// current ad statistics panel
			$ads1 = array();
			$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_userid = '".$userdata['user_id']."' AND adverts_expired = '0' ORDER BY adverts_id DESC");
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '".$data['adverts_userid']."'");
				$data2 = dbarray($result2);
				$data['user_name'] = $data2['user_name'];
				if ($data['adverts_shown'] == 0)
					$percent = 0;
				else
					$percent = substr(100 * $data['adverts_clicks'] / $data['adverts_shown'], 0, 5);
				$data['percentage'] = $percent;
				$contract_type = $contract_types[$data['adverts_contract']];
				switch ($data['adverts_contract']) {
					case 0:
						break;
					case 1:
						if ($data['adverts_contract_start'] > time()) {
							$contract_type .= " (".$locale['472']." ".showdate("%d-%m-%Y", $data['adverts_contract_start']).")";
						} elseif ($data['adverts_contract_end'] != 0) {
							$contract_type .= " (".$locale['471']." ".showdate("%d-%m-%Y", $data['adverts_contract_end']).")";
						} else {
							$contract_type .= " (".$locale['478'].")";
						}
						break;
					case 2:
						$contract_type .= " (".($data['adverts_sold']-$data['adverts_shown'])." ".$locale['477'].")";
						break;
				}
				$data['contract_type'] = $contract_type;
				$adverts_url = $data['adverts_url'];
				if(strtolower(substr($adverts_url,0,7)) != "http://" && strtolower(substr($adverts_url,0,8)) != "https://") {
					// if not add it (assume http://)
					$adverts_url = "http://" . $adverts_url;
				} 
				$data['adverts_url'] = $adverts_url;
				$data['ad_location'] = $ad_locations[$data['adverts_location']];
				$ads1[] = $data;
			}
			$variables['ads1'] = $ads1;
			
			// expired ad statistics panel
			$ads2 = array();
			$result = dbquery("SELECT * FROM ".$db_prefix."adverts WHERE adverts_userid = '".$userdata['user_id']."' AND adverts_expired = '1' ORDER BY adverts_id DESC");
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id = '".$data['adverts_userid']."'");
				$data2 = dbarray($result2);
				if ($data['adverts_shown'] == 0)
					$percent = 0;
				else
					$percent = substr(100 * $data['adverts_clicks'] / $data['adverts_shown'], 0, 5);
				$data['percentage'] = $percent;
				$contract_type = $contract_types[$data['adverts_contract']];
				switch ($data['adverts_contract']) {
					case 0:
						break;
					case 1:
						if ($data['adverts_contract_start'] > time()) {
							$contract_type .= " (".$locale['472']." ".showdate("%d-%m-%Y", $data['adverts_contract_start']).")";
						} elseif ($data['adverts_contract_end'] != 0) {
							$contract_type .= " (".$locale['471']." ".showdate("%d-%m-%Y", $data['adverts_contract_end']).")";
						} else {
							$contract_type .= " (".$locale['478'].")";
						}
						break;
					case 2:
						$contract_type .= " (".($data['adverts_sold']-$data['adverts_shown'])." ".$locale['477'].")";
						break;
				}
				$data['contract_type'] = $contract_type;
				$adverts_url = $data['adverts_url'];
				if(strtolower(substr($adverts_url,0,7)) != "http://" && strtolower(substr($adverts_url,0,8)) != "https://") {
					// if not add it (assume http://)
					$adverts_url = "http://" . $adverts_url;
				} 
				$data['adverts_url'] = $adverts_url;
				$data['ad_location'] = $ad_locations[$data['adverts_location']];
				$ads2[] = $data;
			}
			$variables['ads2'] = $ads2;
		}
	}
}

// store the variables used for this template
$variables['is_client'] = $is_client;
$variables['is_updated'] = $is_updated;
$variables['errormessage'] = $errormessage;

// define the body panel variables
$template_panels[] = array('type' => 'body', 'name' => 'advertising', 'template' => 'main.advertising.tpl', 'locale' => PATH_LOCALE.LOCALESET."admin/adverts.php");
$template_variables['advertising'] = $variables;

// Call the theme code to generate the output for this webpage
require_once PATH_THEME."/theme.php";
?>