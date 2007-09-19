<?php
$locale['400'] = "Bli anv�ndare";
$locale['401'] = "Aktivera anv�ndarkonto";
// Registration Errors
$locale['402'] = "Du m�ste v�lja ett anv�ndarnamn, ett l�senord, samt ange en epostadress";
$locale['403'] = "Ditt anv�ndarnamn inneh�ller otill�tna tecken";
$locale['404'] = "L�senorden �r inte identiska.";
$locale['405'] = "Ogiltigt l�senord, endast alfanumeriska tecken f�r anv�ndas.<br>
L�senordet m�ste best� av minst 6 tecken.";
$locale['406'] = "Din epostadress f�refaller ej giltlig.";
$locale['407'] = "Tyv�rr, anv�ndarnamnet ".(isset($_POST['username']) ? $_POST['username'] : "")." �r upptaget.";
$locale['408'] = "Tyv�rr, men epostadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." �r upptagen.";
$locale['409'] = "En anv�ndare med ett inaktivt konto �r redan registrerad med denna epostadress.";
$locale['410'] = "S�kerhetskoden �r felaktig.";
$locale['411'] = "Din epostadress eller epostdom�n �r sp�rrad.";
// Email Message
$locale['449'] = "V�lkommen till ".$settings['sitename'];
$locale['450'] = "Hej ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
V�lkommen till ".$settings[sitename].", h�r �r dina inloggningsuppgifter:\n
Anv�ndarnamn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
L�senord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktivera ditt medlemskap genom att klicka p� f�ljande l�nk:\n";
// Registration Success/Fail
$locale['451'] = "Registreringen fullst�ndig";
$locale['452'] = "Du kan logga in nu.";
$locale['453'] = "En administrat�r kommer att aktivera ditt konto snarast.";
$locale['454'] = "Registreringen �r n�stan klar, du kommer att f� epost inneh�llande dina inloggningsdetaljer tillsammans med en verifieringsl�nk.";
$locale['455'] = "Ditt konto �r verifierat";
$locale['456'] = "Registreringen misslyckades";
$locale['457'] = "Det gick inte att skicka epost. Kontakta <a href='mailto:".$settings['siteemail']."'>sidans administrat�r</a>.";
$locale['458'] = "Registreringen misslyckades p� grund av f�ljande orsak:";
$locale['459'] = "F�rs�k igen";
// Register Form
$locale['500'] = "Skriv in dina uppgifter nedan. ";
$locale['501'] = "Ett verifieringsbrev skickas till den epostadress du har uppgivit...";
$locale['502'] = "Alla markerade f�lt <span style='color:#ff0000;'>*</span> skall fyllas i. OBS! Anv�ndarnamn och l�senord �r skiftl�gesk�nsliga!";
$locale['503'] = " Du kan l�gga till ytterligare information genom att v�lja Redigera profil n�r du har loggat p�.";
$locale['504'] = "S�kerhetskod:";
$locale['505'] = "Skriv in s�kerhetskoden:";
$locale['506'] = "Registrera";
$locale['507'] = "Registreringssystemet �r tillf�lligt deaktiverat.";
// Validation Errors
$locale['550'] = "Du m�ste ange ett anv�ndarnamn.";
$locale['551'] = "Du m�ste ange ett l�senord.";
$locale['552'] = "Du m�ste ange en epostadress.";
?>
