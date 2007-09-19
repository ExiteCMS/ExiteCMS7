<?php
// Member Management Options
$locale['400'] = "Mitglieder";
$locale['401'] = "Benutzer";
$locale['402'] = "Hinzuf�gen";
$locale['403'] = "Benutzer Typ";
$locale['404'] = "Optionen";
$locale['405'] = "Ansehen";
$locale['406'] = "Bearbeiten";
$locale['407'] = "Entsperren";
$locale['408'] = "Sperren";
$locale['409'] = "L�schen";
$locale['410'] = "Es gibt keine Benutzernamen beginnend mit ";
$locale['411'] = "Zeige alle";
$locale['412'] = "Aktivieren";
// Ban/Unban/Delete Member
$locale['420'] = "Sperre verh�ngt";
$locale['421'] = "Sperre entfernt";
$locale['422'] = "Mitglied gel�scht";
$locale['423'] = "Sind Sie sicher, dass Sie das Mitglied l�schen m�chten?";
$locale['424'] = "Mitglied aktiviert";
$locale['425'] = "Account aktiviert am ";
$locale['426'] = "Hallo [USER_NAME],\n
Ihr Account auf ".$settings['sitename']." wurde aktiviert.\n
Sie k�nnen sich nun mit Ihrem gew�hlten Namen und Passwort einloggen.\n
Viele Gr��e,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Mitglied bearbeiten";
$locale['431'] = "Mitglieder Details aktualisiert";
$locale['432'] = "Zur�ck zum Mitglieder-Admin";
$locale['433'] = "Zur�ck zum Admin Index";
$locale['434'] = "Es ist nicht m�glich die Mitglieder Details zu aktualisieren:";
// Extra Edit Member Details form options
$locale['440'] = "�nderungen speichern";
// Update Profile Errors
$locale['450'] = "Kann prim�ren Administrator nicht bearbeiten.";
$locale['451'] = "Sie m�ssen einen Benutzernamen und eine E-Mail Adresse angeben.";
$locale['452'] = "Benutzername enth�lt ung�ltige Zeichen.";
$locale['453'] = "Der Benutzername ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." ist in Benutzung.";
$locale['454'] = "Falsche E-Mail Adresse.";
$locale['455'] = "Die E-Mail Adresse ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." ist in Benutzung.";
$locale['456'] = "Neues Passwort stimmt nicht �berein.";
$locale['457'] = "Falsches Passwort, benutzen Sie nur alphanumerische Zeichen.<br>
Das Passwort muss mindestens 6 Zeichen lang sein.";
$locale['458'] = "<b>Warnung:</b> unerwartete Scriptausf�hrung.";
// View Member Profile
$locale['470'] = "Mitglieder Profile";
$locale['472'] = "Statistik";
$locale['473'] = "Benutzergruppen";
// Add Member Errors
$locale['480'] = "Mitglied hinzuf�gen";
$locale['481'] = "Der Mitgliedsaccount wurde erstellt.";
$locale['482'] = "Der Mitgliedsaccount konnte nicht erstellt werden.";
?>
