<?php
$locale['400'] = "Bliv registreret bruger";
$locale['401'] = "Aktiver konto";
// Registration Errors
$locale['402'] = "Du skal opgive et brugernavn, et kodeord og en mailadresse.";
$locale['403'] = "Brugernavnet indeholder forkerte karakterer.";
$locale['404'] = "De to kodeord er ikke identiske.";
$locale['405'] = "Forkert kodeord. Du m� kun bruge alfanumeriske karakterer.<br>
Et kodeord skal v�re mindst 6 karakterer langt.";
$locale['406'] = "Det ser ud, som om der er fejl i din mailadresse.";
$locale['407'] = "Beklager, men brugernavnet ".(isset($_POST['username']) ? $_POST['username'] : "")." anvendes allerede.";
$locale['408'] = "Beklager, men mailadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." anvendes allerede.";
$locale['409'] = "En ikke aktiveret konto er blevet oprettet med denne mailadresse.";
$locale['410'] = "Sikkerhedskoden er forkert.";
$locale['411'] = "Din mailadresse eller dit maildom�ne er udelukket.";
// Email Message
$locale['449'] = "Velkommen som bruger p� ".$settings['sitename'];
$locale['450'] = "Hej ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Velkommen som medlem p� ".$settings['sitename'].". Her er dine p�logningsoplysninger:\n
Brugernavn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Kodeord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
V�r s� venlig at aktivere din konto ved at trykke p� dette link:\n";
// Registration Success/Fail
$locale['451'] = "Oprettelse gennemf�rt";
$locale['452'] = "Du kan nu logge p�.";
$locale['453'] = "En administrator vil aktivere din konto meget snart.";
$locale['454'] = "Din tilmelding er n�sten gennemf�rt. Du vil modtage en email med dine p�logningsoplysninger og et link, s� du kan aktivere din brugerkonto.";
$locale['455'] = "Din tilmelding er godkendt.";
$locale['456'] = "Brugeroprettelse kunne ikke gennemf�res";
$locale['457'] = "Vi kunne ikke sende dig en mail. Kontakt <a href='mailto:".$settings['siteemail']."'>sidens administrator</a>.";
$locale['458'] = "Brugeroprettelse gik galt af f�lgende �rsag(er):";
$locale['459'] = "Pr�v igen";
// Register Form
$locale['500'] = "Indskriv de n�dvendige oplysninger herunder. ";
$locale['501'] = "En email vil blive sendt til den adresse, du har opgivet. ";
$locale['502'] = "Felter markeret med <span style='color:#ff0000;'>*</span> skal udfyldes.
Der skelnes mellem store og sm� bogstaver i brugernavn og kodeord.";
$locale['503'] = " Du kan tilf�je informationer ved at �bne Rediger profil, n�r du f�rst er logget p�.";
$locale['504'] = "Sikkerhedskode:";
$locale['505'] = "Skriv sikkerhedskode:";
$locale['506'] = "Opret bruger";
$locale['507'] = "Brugeroprettelse er sl�et fra i �jeblikket.";
// Validation Errors
$locale['550'] = "Du skal angive et brugernavn.";
$locale['551'] = "Du skal angive et kodeord.";
$locale['552'] = "Du skal angive en mailadresse.";
?>
