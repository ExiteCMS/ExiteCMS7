<?php
$locale['400'] = "Registrieren";
$locale['401'] = "Aktiviere Account";
// Registration Errors
$locale['402'] = "Sie m�ssen einen Benutzernamen, Passwort und eine E-Mail Adresse angeben.";
$locale['403'] = "Benutzername enth�lt falsche Zeichen.";
$locale['404'] = "Ihre zwei Passw�rter stimmen nicht �berein.";
$locale['405'] = "Falsches Passwort, benutzen Sie nur alphanumerische Zeichen.<br>
Pa�wort mu� mindestens 6 Zeichen lang sein.";
$locale['406'] = "Ihre E-Mail Adresse scheint nicht g�ltig zu sein.";
$locale['407'] = "Entschuldigung, der Benutzername ".(isset($_POST['username']) ? $_POST['username'] : "")." wird schon benutzt.";
$locale['408'] = "Entschuldigung, die E-Mail Adresse ".(isset($_POST['email']) ? $_POST['email'] : "")." wird schon benutzt.";
$locale['409'] = "Ein inaktiver Account ist registriert mit dieser E-Mail Adresse.";
$locale['410'] = "Falscher �berpr�fungscode.";
$locale['411'] = "Ihre E-Mail Adresse oder E-Mail Domain ist auf der Blacklist.";
// Email Message
$locale['449'] = "Welcome to ".$settings['sitename'];
$locale['450'] = "Hallo ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Willkommen auf ".$settings['sitename'].". Hier sind deine Login Daten:\n
Username: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Passwort: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Account mit folgendem Link aktivieren:\n";
// Registration Success/Fail
$locale['451'] = "Registrierung komplett";
$locale['452'] = "Sie k�nnen sich nun einloggen.";
$locale['453'] = "In K�rze wird ein Administrator Ihren Account freischalten.";
$locale['454'] = "Ihre Registrierung ist fast komplett, Sie erhalten eine E-Mail mit Ihren Logindetails und einen Link zur Best�tigung Ihres Accounts.";
$locale['455'] = "Ihr Account ist best�tigt.";
$locale['456'] = "Registrierung fehlgeschlagen";
$locale['457'] = "Senden der Mail fehlgeschlagen, bitte kontaktieren Sie den <a href='mailto:".$settings['siteemail']."'>Seiten-Administrator</a>.";
$locale['458'] = "Registrierung aus folgendem Grund fehlgeschlagen:";
$locale['459'] = "Bitte versuchen Sie es nochmal";
// Register Form
$locale['500'] = "Bitte tragen Sie Details weiter unten ein. ";
$locale['501'] = "Eine �berpr�fungs-E-Mail wird an die angegebene E-Mail Adresse gesendet. ";
$locale['502'] = "Markierte Felder <span style='color:#ff0000;'>*</span> m�ssen ausgef�llt sein.
Ihr Benutzername und Passwort sind case-sensitive.";
$locale['503'] = " Sie k�nnen mehr Informationen eingeben, wenn Sie nach dem Einloggen Ihr Profil bearbeiten.";
$locale['504'] = "�berpr�fungscode:";
$locale['505'] = "Eingabe des �berpr�fungscode:";
$locale['506'] = "Registrieren";
$locale['507'] = "Die Registrierung ist vor�bergehend abgeschaltet.";
// Validation Errors
$locale['550'] = "Bitte geben Sie einen Benutzernamen an.";
$locale['551'] = "Bitte geben Sie ein Passwort an.";
$locale['552'] = "Bitte geben Sie eine E-Mail Adresse an.";
?>