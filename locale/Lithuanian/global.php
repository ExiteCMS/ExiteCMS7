<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/

// Locale Settings
setlocale(LC_TIME, "lt_LT"); // Linux Server (Windows may differ)
$locale['charset'] = "windows-1257";
$locale['tinymce'] = "lt";
$locale['phpmailer'] = "lt";

// Full & Short Months
$locale['months'] = "&nbsp|Sausis|Vasaris|Kovas|Balandis|Gegu��|Bir�elis|Liepa|Rugpj�tis|Rugs�jis|Spalis|Lapkritis|Gruodis";
$locale['shortmonths'] = "&nbsp|Sau|Vas|Kov|Bal|Geg|Bir|Lie|Rug|Rgs|Spa|Lap|Gru";

// Standard User Levels
$locale['user0'] = "Sve�ias";
$locale['user1'] = "Narys";
$locale['user2'] = "Administratorius";
$locale['user3'] = "Super administratorius";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderatorius";
// Navigation
$locale['001'] = "Navigacija";
$locale['002'] = "N�ra apib�dint� nuorod�";
$locale['003'] = "Tik nariams";
$locale['004'] = "�i panel� neturi turinio";
// Users Online
$locale['010'] = "Vartotoj� tinkle";
$locale['011'] = "Prisijungusi� sve�i�: ";
$locale['012'] = "Prisijungusi� nari�: ";
$locale['013'] = "Prisijungusi� nari� n�ra";
$locale['014'] = "Registruoti nariai: ";
$locale['015'] = "Neaktyv�s nariai: ";
$locale['016'] = "Naujausias narys: ";
// Sidebar & Other Titles
$locale['020'] = "Forumo prane�imai";
$locale['021'] = "Nauji prane�imai";
$locale['022'] = "Populiar�s prane�imai";
$locale['023'] = "Paskutiniai straipsniai";
$locale['024'] = "Pasisveikinimas";
$locale['025'] = "Paskutiniai aktyv�s forumo prane�imai";
$locale['026'] = "Mano paskutinis prane�imas";
$locale['027'] = "Mano paskutin� �inut�";
$locale['028'] = "Nauji prane�imai";
// Forum List Texts
$locale['030'] = "Forumas";
$locale['031'] = "Tema";
$locale['032'] = "Skaityta";
$locale['033'] = "Atsakymai";
$locale['034'] = "Paskutinis prane�imas";
$locale['035'] = "Tema";
$locale['036'] = "Para�yta";
$locale['037'] = "J�s neturite sukurt� tem� forume.";
$locale['038'] = "J�s neturite para�yt� prane�im� forume.";
$locale['039'] = "Yra %u naujas(i) prane�imas(ai) nuo paskutinio j�s� apsilankymo.";
// News & Articles
$locale['040'] = "Para�� ";
$locale['041'] = " ";
$locale['042'] = "Skaityti daugiau";
$locale['043'] = " Komentarai";
$locale['044'] = " Skaityta";
$locale['045'] = "Spausdinti";
$locale['046'] = "Naujienos";
$locale['047'] = "Kol kas naujien� n�ra";
$locale['048'] = "Redaguoti";
// Prev-Next Bar
$locale['050'] = "Ankst";
$locale['051'] = "Sek";
$locale['052'] = "Puslapis ";
$locale['053'] = " i� ";
// User Menu
$locale['060'] = "Prisijungti";
$locale['061'] = "Nario vardas";
$locale['062'] = "Slapta�odis";
$locale['063'] = "Atsiminti mane";
$locale['064'] = "Prisijungti";
$locale['065'] = "Dar ne narys?<br><a href='".BASEDIR."register.php' class='side'><b>Registruokis</b></a>";
$locale['066'] = "Pamir�ai slapta�od�?<a href='".BASEDIR."lostpassword.php' class='side'><br><b>Papra�yk naujo</b></a>";
//
$locale['080'] = "Redaguoti apra�ym�";
$locale['081'] = "Asmenin�s �inut�s";
$locale['082'] = "Nari� s�ra�as";
$locale['083'] = "Administracijos panel�";
$locale['084'] = "Atsijungti";
$locale['085'] = "J�s turite %u nauj�(as) ";
$locale['086'] = "�inut�";
$locale['087'] = "�inutes";
// Poll
$locale['100'] = "Apklausa";
$locale['101'] = "�skai�iuoti bals�";
$locale['102'] = "Nor�damas balsuoti turite prisijungti.";
$locale['103'] = "Balsuoti";
$locale['104'] = "Balsai";
$locale['105'] = "Balsai: ";
$locale['106'] = "Prad�tas: ";
$locale['107'] = "Baigtas: ";
$locale['108'] = "Apklaus� archyvas";
$locale['109'] = "Pasirinkti apklaus� per�i�rai i� s�ra�o:";
$locale['110'] = "�i�r�ti";
$locale['111'] = "Per�i�r�ti apklaus�";
// Shoutbox
$locale['120'] = "�aukykla";
$locale['121'] = "Vardas:";
$locale['122'] = "�inut�:";
$locale['123'] = "Ra�yti";
$locale['124'] = "Pagalba";
$locale['125'] = "Jei norite ra�yti �inutes, turite prisijungti.";
$locale['126'] = "�aukyklos archyvas";
$locale['127'] = "N�ra nauj� �inu�i�.";
// Footer Counter
$locale['140'] = "Unikal�s apsilankymai";
$locale['141'] = $settings['sitename']." Unikali� apsilankym�";
// Admin Navigation
$locale['150'] = "Administracija";
$locale['151'] = "Gr��ti � tinklap�";
$locale['152'] = "Administracijos panel�s";
// Miscellaneous
$locale['190'] = "Tinklapio i�jungimo re�imas aktyvuotas";
$locale['191'] = "J�s� IP adresas juod�jame s�ra�e.";
$locale['192'] = "Atsijungti kaip ";
$locale['193'] = "Prisijungti kaip ";
$locale['194'] = "J�s� dalyvavimas sustabdytas.";
$locale['195'] = "�is prisijungimas neaktyvuotas.";
$locale['196'] = "Neteisingas nario vardas arba slapta�odis.";
$locale['197'] = "Palaukite, kol mes atidarin�jame...<br><br>
[ <a href='index.php'>Arba spauskite, jei nenorite laukti</a> ]";
$locale['198'] = "<b>D�mesio:</b> aptiktas setup.php, nedelsiant j� i�trinkite";
?>