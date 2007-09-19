<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/
// Locale Settings
setlocale(LC_TIME, "pl", "pl_PL", "polish"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-2";
$locale['tinymce'] = "pl";
$locale['phpmailer'] = "pl";

// Full & Short Months
$locale['months'] = "&nbsp|Stycze�|Luty|Marzec|Kwiecie�|Maj|Czerwiec|Lipiec|Sierpie�|Wrzesie�|Pa�dziernik|Listopad|Grudzie�";
$locale['shortmonths'] = "&nbsp|sty|lut|mar|kwi|maj|czer|lip|sier|wrze|pa�|lis|gru";

// Standard User Levels
$locale['user0'] = "Publiczne";
$locale['user1'] = "U�ytkownik";
$locale['user2'] = "Administrator";
$locale['user3'] = "Super Administrator";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Nawigacja";
$locale['002'] = "Brak link�w\n";
$locale['003'] = "Dla U�ytkownik�w";
$locale['004'] = "Brak zawarto�ci dla tego panelu";
// Users Online
$locale['010'] = "U�ytkownik�w Online";
$locale['011'] = "Go�ci Online: ";
$locale['012'] = "U�ytkownicy Online: ";
$locale['013'] = "Brak U�ytkownik�w Online";
$locale['014'] = "Zarejestrowanch Uzytkownik�w: ";
$locale['015'] = "Nieaktywowany U�ytkownik: ";
$locale['016'] = "Najnowszy U�ytkownik: ";
// Sidebar & Other Titles
$locale['020'] = "W�tki na Forum";
$locale['021'] = "Najnowsze Tematy";
$locale['022'] = "Najciekawsze Tematy";
$locale['023'] = "Ostatnie Artyku�y";
$locale['024'] = "Witamy";
$locale['025'] = "Ostatnie aktywne Tematy";
$locale['026'] = "Moje ostatnie Tematy";
$locale['027'] = "Moje ostatnie Posty";
$locale['028'] = "Nowe Posty";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "W�tek";
$locale['032'] = "Ogl�dane";
$locale['033'] = "Odpowiedzi";
$locale['034'] = "Ostatni Post";
$locale['035'] = "Temat";
$locale['036'] = "Dodano";
$locale['037'] = "Nie masz jeszcze �adnych w�tk�w na Forum.";
$locale['038'] = "Nie masz jeszcze �adnego posta na Forum.";
$locale['039'] = "Jest %u nowych post�w od Twojej ostatniej wizyty.";
// News & Articles
$locale['040'] = "Dodane przez ";
$locale['041'] = "dnia ";
$locale['042'] = "Czytaj wi�cej";
$locale['043'] = " Komentarzy";
$locale['044'] = " Czyta�";
$locale['045'] = "Drukuj";
$locale['046'] = "News";
$locale['047'] = "Brak News�w";
$locale['048'] = "Edytuj";
// Prev-Next Bar
$locale['050'] = "Poprzednia";
$locale['051'] = "Nast�pna";
$locale['052'] = "Strona ";
$locale['053'] = " z ";
// User Menu
$locale['060'] = "Logowanie";
$locale['061'] = "Nazwa U�ytkownika";
$locale['062'] = "Has�o";
$locale['063'] = "Zapami�taj mnie";
$locale['064'] = "Loguj";
$locale['065'] = "Nie jeste� jeszcze naszym U�ytkownikiem?<br><a href='".BASEDIR."register.php' class='side'>Kilknij TUTAJ</a> �eby si� zarejestrowa�.";
$locale['066'] = "Zapomniane has�o?<br>Wy�lemy nowe, kliknij <a href='".BASEDIR."lostpassword.php' class='side'>TUTAJ</a>.";
//
$locale['080'] = "Edytuj Profil";
$locale['081'] = "Prywatne Wiadomo�ci";
$locale['082'] = "Lista U�ytkownik�w";
$locale['083'] = "Panel Administracyjny";
$locale['084'] = "Wyloguj";
$locale['085'] = "Masz %u nowych ";
$locale['086'] = "wiadomo��";
$locale['087'] = "wiadomo�ci";
// Poll
$locale['100'] = "Ankieta dla U�ytkownik�w";
$locale['101'] = "Zag�osuj";
$locale['102'] = "Musisz si� zalogowa�, �eby m�c g�osowa� w tej Ankiecie.";
$locale['103'] = "G�osuj";
$locale['104'] = "G�os�w";
$locale['105'] = "G�os�w: ";
$locale['106'] = "Rozpocz�ta: ";
$locale['107'] = "Zako�czona: ";
$locale['108'] = "Archiwum Ankiet";
$locale['109'] = "Wybierz Ankiet�:";
$locale['110'] = "Zobacz";
$locale['111'] = "Zobacz Ankiet�";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Nick:";
$locale['122'] = "Wiadomo��:";
$locale['123'] = "Wy�lij";
$locale['124'] = "Pomoc";
$locale['125'] = "Tylko zalogowani mog� dodawa� posty w shoutboksie.";
$locale['126'] = "Archiwum";
$locale['127'] = "Brak post�w.";
// Footer Counter
$locale['140'] = "Unikalna wizyta";
$locale['141'] = "Unikalnych wizyt";
// Admin Navigation
$locale['150'] = "Menu Admina";
$locale['151'] = "Powr�t do Strony G��wnej";
$locale['152'] = "Panel Admina";
// Miscellaneous
$locale['190'] = "Maintenance Mode, czyli Tryb Bezpiecze�stwa - Aktywowany";
$locale['191'] = "Zosta�e� zabanowany na IP.";
$locale['192'] = "Wylogowany jako ";
$locale['193'] = "Zalogowany jako ";
$locale['194'] = "To konto jest podejrzane.";
$locale['195'] = "To konto nie zosta�o aktywowane.";
$locale['196'] = "Nieprawid�owy Nick lub Has�o.";
$locale['197'] = "Za chwil� nast�pi przekierowanie...<br><br>
[ <a href='index.php'>Kliknij tutaj, je�li nie chcesz czeka�</a> ]";
$locale['198'] = "<b>OSTRZE�ENIE:</b> zosta� wykryty plik setup.php , prosz� usun�� go jak najszybciej z serwera";
?>
