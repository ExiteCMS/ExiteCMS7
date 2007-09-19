<?php
// Member Management Options
$locale['400'] = "Anv�ndaradministration";
$locale['401'] = "Anv�ndare";
$locale['402'] = "L�gg till";
$locale['403'] = "Anv�ndarstatus";
$locale['404'] = "Inst�llningar";
$locale['405'] = "Granska";
$locale['406'] = "Redigera";
$locale['407'] = "Upph�v uteslutning";
$locale['408'] = "Uteslut anv�ndare";
$locale['409'] = "Radera";
$locale['410'] = "Det finns inget anv�ndarnamn som b�rjar med ";
$locale['411'] = "Visa alla";
$locale['412'] = "Aktivera";
// Ban/Unban/Delete Member
$locale['420'] = "Uteslutning genomf�rd";
$locale['421'] = "Uteslutning upph�vd";
$locale['422'] = "Anv�ndaren �r raderad";
$locale['423'] = "�r du s�ker p� att du vill radera denna anv�ndare?";
$locale['424'] = "Anv�ndaren aktiverad";
$locale['425'] = "Anv�ndarkontot aktiverat den ";
$locale['426'] = "Hej [USER_NAME],\n
Ditt konto ".$settings['sitename']." har blivit aktiverat\n
Du kan logga in med ditt anv�ndarnamn och l�senord.\n
Med v�nliga h�lsningar,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Redigera anv�ndaruppgifter";
$locale['431'] = "Anv�ndarupplysningar �r �ndrade";
$locale['432'] = "Tillbaka till anv�ndaradministration";
$locale['433'] = "Tillbaka till administrationspanel";
$locale['434'] = "Det gick inte att �ndra anv�ndaruppgifter:";
// Extra Edit Member Details form options
$locale['440'] = "Spara �ndringar";
// Update Profile Errors
$locale['450'] = "Huvudadministrat�ren kan ej �ndras.";
$locale['451'] = "Du m�ste ange ett anv�ndarnamn och en epostadress.";
$locale['452'] = "Anv�ndarnamnet inneh�ller ogiltiga tecken.";
$locale['453'] = "Anv�ndarnamnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." anv�nds redan.";
$locale['454'] = "Felaktig epostadress.";
$locale['455'] = "Epostadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." anv�nds redan.";
$locale['456'] = "L�senorden �r inte identiska.";
$locale['457'] = "Ogiltigt l�senord, endast alfanumeriska tecken f�r anv�ndas.
<br>L�senordet m�ste best� av minst 6 tecken.";
$locale['458'] = "<b>Varning:</b> ov�ntad scripth�ndelse.";
// View Member Profile
$locale['470'] = "Anv�ndaruppgifter:";
$locale['472'] = "Statistik";
$locale['473'] = "Anv�ndargrupper";
// Update Profile Errors
$locale['480'] = "L�gg till anv�ndare";
$locale['481'] = "Anv�ndarkontot �r uppr�ttat.";
$locale['482'] = "Anv�ndarkontot kunde ej uppr�ttas.";
?>
