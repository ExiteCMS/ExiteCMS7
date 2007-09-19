<?php
/*
Swedish Language Fileset
Produced by Happy Svensson (KEFF)
Email: keff@php-fusion.se
Web: http://www.php-fusion.se
*/

// Locale Settings
setlocale(LC_TIME, "swedish"); // Linuxsystem, Windows kan variera
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "sv";
$locale['phpmailer'] = "en";

//Full & Short Months
$locale['months'] = "&nbsp;|Januari|Februari|Mars|April|Maj|Juni|Juli|Augusti|September|Oktober|November|December";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Mar|Apr|Maj|Jun|Jul|Aug|Sept|Okt|Nov|Dec";

// Standard User Levels
$locale['user0'] = "Bes�kare";
$locale['user1'] = "Anv�ndare";
$locale['user2'] = "Administrat�r";
$locale['user3'] = "Superadministrat�r";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Navigation";
$locale['002'] = "Det finns inga l�nkar definierade";
$locale['003'] = "Endast f�r registrerade anv�ndare";
$locale['004'] = "Denna panel har inget inneh�ll �n";
// Users Online
$locale['010'] = "Inloggade anv�ndare";
$locale['011'] = "G�ster: ";
$locale['012'] = "Inloggade anv�ndare: ";
$locale['013'] = "Inga anv�ndare inloggade";
$locale['014'] = "Antal registrerade anv�ndare: ";
$locale['015'] = "Inaktiverade anv�ndare: ";
$locale['016'] = "Senast registrerade anv�ndare: ";
// Sidebar
$locale['020'] = "Senaste debatter";
$locale['021'] = "Nyaste debatter";
$locale['022'] = "Popul�raste debatter";
$locale['023'] = "Nyaste artiklar";
$locale['024'] = "V�lkommen";
$locale['025'] = "Senaste aktiva debatt�mnen";
$locale['026'] = "Mina senaste debatter";
$locale['027'] = "Mina senaste inl�gg";
$locale['028'] = "Nya inl�gg";
// Forum List Texts
$locale['030'] = "Debattforum";
$locale['031'] = "�mne";
$locale['032'] = "Antal visningar";
$locale['033'] = "Svar";
$locale['034'] = "Senaste inl�gg";
$locale['035'] = "�mne";
$locale['036'] = "Publicerat";
$locale['037'] = "Du har inte startat ett �mne �nnu.";
$locale['038'] = "Du har inte postat n�gra inl�gg �nnu.";
$locale['039'] = "Det finns %u nya inl�gg postade sedan ditt senaste bes�k.";
// News & Articles
$locale['040'] = "Publicerad av ";
$locale['041'] = " datum: ";
$locale['042'] = "L�s mera";
$locale['043'] = " Kommentarer";
$locale['044'] = " Antal visningar";
$locale['045'] = "Skriv ut";
$locale['046'] = "Nyheter";
$locale['047'] = "Det finns inga nyheter publicerade �nnu";
$locale['048'] = "Redigera";
// Prev-Next Bar
$locale['050'] = "F�reg�ende";
$locale['051'] = "N�sta";
$locale['052'] = "Sida ";
$locale['053'] = " av ";
// User Menu
$locale['060'] = "Logga in";
$locale['061'] = "Anv�ndarnamn";
$locale['062'] = "L�senord";
$locale['063'] = "Spara mitt l�senord";
$locale['064'] = "Logga in";
$locale['065'] = "�r du inte registrerad anv�ndare?<br><a href='".BASEDIR."register.php' class='side'>Klicka h�r</a> f�r att registrera dig.";
$locale['066'] = "F�rlorat l�senordet? <br>Beg�r ett nytt <a href='".BASEDIR."lostpassword.php' class='side'>h�r</a>.";
//
$locale['080'] = "Redigera din profil";
$locale['081'] = "Privata meddelanden";
$locale['082'] = "Anv�ndarlista";
$locale['083'] = "Administrationspanel";
$locale['084'] = "Logga ut";
$locale['085'] = "Det finns %u ";
$locale['086'] = "nytt meddelande";
$locale['087'] = "nya meddelanden";
// Poll
$locale['100'] = "Omr�stning";
$locale['101'] = "R�sta";
$locale['102'] = "Du m�ste logga in f�r att kunna r�sta.";
$locale['103'] = "R�st";
$locale['104'] = "R�ster";
$locale['105'] = "R�ster: ";
$locale['106'] = "P�b�rjad: ";
$locale['107'] = "Avslutad: ";
$locale['108'] = "Arkiv omr�stningar";
$locale['109'] = "V�lj en omr�stning fr�n listan:";
$locale['110'] = "Visa";
$locale['111'] = "Visa omr�stning";
// Shoutbox
$locale['120'] = "Klotterplanket";
$locale['121'] = "Namn:";
$locale['122'] = "Meddelande:";
$locale['123'] = "Klottra!";
$locale['124'] = "Hj�lp";
$locale['125'] = "Du m�ste logga in f�r att skriva ett meddelande.";
$locale['126'] = "Arkiv Klotterplanket";
$locale['127'] = "Inga meddelanden har skickats.";
// Footer Counter
$locale['140'] = "Unikt bes�k";
$locale['141'] = "Unika bes�k";
// Admin Navigation
$locale['150'] = "Administrationspanel";
$locale['151'] = "Tillbaka till huvudsidan";
$locale['152'] = "Administration";

// Miscellaneous
$locale['190'] = "Underh�llsl�ge aktiverat";
$locale['191'] = "Ditt IP - nummer �r f n sp�rrat.";
$locale['192'] = "Loggar ut som ";
$locale['193'] = "Loggar in som ";
$locale['194'] = "Detta anv�ndarkonto �r f n avst�ngt.";
$locale['195'] = "Detta anv�ndarkonto �r inte aktiverat.";
$locale['196'] = "Ogiltigt anv�ndarnamn eller l�senord.";
$locale['197'] = "V�nta medan du f�rflyttas...<br><br>
[ <a href='index.php'>Eller klicka h�r om du inte �nskar v�nta</a> ]";
$locale['198'] = "<b>Varning:</b> filen setup.php �r kvar p� servern, radera den omedelbart!";
?>
