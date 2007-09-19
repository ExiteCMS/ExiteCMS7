<?php
$locale['400'] = "Registrering";
$locale['401'] = "Aktiver konto";
// Registration Errors
$locale['402'] = "Du m� oppgi et brukernavn, et passord og en epostadresse";
$locale['403'] = "Brukernavnet inneholder ugyldige tegn";
$locale['404'] = "Passordene er ikke identiske.";
$locale['405'] = "Ugyldig passord, bare alfanumeriske tegn kan brukes.<br>
Passordet m� best� av minst 6 tegn.";
$locale['406'] = "Din epostadresse ser ikke ut til � v�re gyldig.";
$locale['407'] = "Beklager, brukernavnet ".(isset($_POST['username']) ? $_POST['username'] : "")." er opptatt.";
$locale['408'] = "Beklager, men epostadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." er opptatt.";
$locale['409'] = "En bruker med en inaktiv konto er allerede registrert med denne epostadressen.";
$locale['410'] = "Sikkerthetskoden er feil.";
$locale['411'] = "Din epostadresse eller epostdomene er sperret.";
// Email Message
$locale['449'] = "Velkommen til ".$settings['sitename'];
$locale['450'] = "Hei ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Velkommen til ".$settings['sitename'].". Her er dine innloggingsdetaljer:\n
Brukernavn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Passord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktiver ditt medlemskap ved � klikke p� f�lgende link:\n";
// Registration Success/Fail
$locale['451'] = "Registreringen er fullf�rt";
$locale['452'] = "Du kan logge inn n�.";
$locale['453'] = "En administrator kommer til � aktivere din konto s� fort som mulig.";
$locale['454'] = "Registreringen er nesten ferdig. Du kommer til � f� en epost som inneholder dine innloggingsdetaljer sammen med en verifiseringslink.";
$locale['455'] = "Din konto er verifisert";
$locale['456'] = "Registreringen mislyktes";
$locale['457'] = "Det gikk ikke � sende epost. Kontakt <a href='mailto:".$settings['siteemail']."'>Sidens administrator</a>.";
$locale['458'] = "Registreringen mislyktes p� grunn av f�lgende �rsak(er):";
$locale['459'] = "Fors�k igjen";
// Register Form
$locale['500'] = "Skriv inn dine opplysninger her.";
$locale['501'] = "Et verifiseringsbrev blir sendt til den epostadressen du oppgir.";
$locale['502'] = "Alle felt markert med <span style='color:#ff0000;'>*</span> m� fylles ut. 
OBS! Brukernavn og passord m� skrives inn n�yaktig slik du vil ha det!";
$locale['503'] = "Du kan legge til ytterligere informasjon ved � velge Rediger profil n�r du har logget p�.";
$locale['504'] = "Sikkerhetskode:";
$locale['505'] = "Skriv inn sikkerhetskoden:";
$locale['506'] = "Registrer";
$locale['507'] = "Registreringssystemet er for tiden deaktivert.";
// Validation Errors
$locale['550'] = "Du m� oppgi et brukernavn.";
$locale['551'] = "Du m� oppgi et passord.";
$locale['552'] = "Du m� oppgi en epostadresse.";
?>