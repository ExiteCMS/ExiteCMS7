<?php
// Helyi be�ll�t�sok
setlocale(LC_TIME, "hu", "hu_HU", "hungarian"); // Linux Srerveren (Windows alatt lehet hogy m�s)
$locale['charset'] = "iso-8859-2";
$locale['tinymce'] = "hu";
$locale['phpmailer'] = "hu";

// Teljes & r�vid�tett h�napok
$locale['months'] = "&nbsp|janu�r|febru�r|m�rcius|�prilis|m�jus|j�nius|j�lius|augusztus|szeptember|okt�ber|november|december";
$locale['shortmonths'] = "&nbsp|jan|febr|m�rc|�pr|m�j|j�n|j�l|aug|szept|okt|nov|dec";

// Felhaszn�l�i rangok
$locale['user0'] = "Vend�g";
$locale['user1'] = "Tag";
$locale['user2'] = "Adminisztr�tor";
$locale['user3'] = "F� Adminisztr�tor";
// F�rum moder�tor
$locale['userf1'] = "Moder�tor";
// Navig�ci�
$locale['001'] = "Navig�ci�";
$locale['002'] = "Nincs megadva link\n";
$locale['003'] = "Csak regisztr�lt tagoknak";
$locale['004'] = "Ez a panel �res";
// Online felhaszn�l�k
$locale['010'] = "Online felhaszn�l�k";
$locale['011'] = "Vend�g: ";
$locale['012'] = "Tag: ";
$locale['013'] = "Nincs Online tag";
$locale['014'] = "Regisztr�ltak: ";
$locale['015'] = "Nem aktiv�ltak: ";
$locale['016'] = "Leg�jabb tag: ";
// Sidebar & egy�b feliratok
$locale['020'] = "F�rumt�m�k";
$locale['021'] = "Leg�jabb t�m�k";
$locale['022'] = "Legn�pszer�bb t�m�k";
$locale['023'] = "Leg�jabb cikkek";
$locale['024'] = "�dv�zlet";
$locale['025'] = "Utols� akt�v f�rumt�m�k";
$locale['026'] = "Legut�bbi f�rumt�m�im";
$locale['027'] = "Legut�bbi hozz�sz�l�saim";
$locale['028'] = "�j hozz�sz�l�sok";
// F�rum t�m�k list�ja
$locale['030'] = "F�rum";
$locale['031'] = "T�ma";
$locale['032'] = "Megnyit�s";
$locale['033'] = "V�lasz";
$locale['034'] = "Utols� �zenet";
$locale['035'] = "T�rgy";
$locale['036'] = "�rta";
$locale['037'] = "M�g egy f�rumt�m�t sem ind�tott�l";
$locale['038'] = "M�g egy hozz�sz�l�sod sincs a f�rumban";
$locale['039'] = "%u �j hozz�sz�l�s utols� l�togat�sod �ta";
// H�rek & cikkek
$locale['040'] = " ";
$locale['041'] = " - ";
$locale['042'] = "R�szletek";
$locale['043'] = " hozz�sz�l�s";
$locale['044'] = " megnyit�s";
$locale['045'] = "Nyomtathat� v�ltozat";
$locale['046'] = "H�rek";
$locale['047'] = "Az oldalon jelenleg nincsenek h�rek";
$locale['048'] = "Szerkeszt�s";
// Prev-Next Bar
$locale['050'] = "El�z�";
$locale['051'] = "K�vetkez�";
$locale['052'] = "Oldal: ";
$locale['053'] = " / ";
// Login men�
$locale['060'] = "Bejelentkez�s";
$locale['061'] = "Felhaszn�l�n�v";
$locale['062'] = "Jelsz�";
$locale['063'] = "Eml�kezzen r�m";
$locale['064'] = "Bejelentkez�s";
$locale['065'] = "M�g nem regisztr�lt�l?<br><a href='".BASEDIR."register.php' class='side'>Kattints ide</a>!";
$locale['066'] = "Elfelejtetted jelszavad?<br><a href='".BASEDIR."lostpassword.php' class='side'>K�rj �jat itt</a>.";
// Felhaszn�l�i men�
$locale['080'] = "Profil szerkeszt�se";
$locale['081'] = "Priv�t �zenetek";
$locale['082'] = "Regisztr�lt tagok";
$locale['083'] = "Adminisztr�ci�";
$locale['084'] = "Kijelentkez�s";
$locale['085'] = "%u �j �zeneted van";
$locale['086'] = "";
$locale['087'] = "";
// Szavaz�s
$locale['100'] = "Szavaz�s";
$locale['101'] = "Szavazok";
$locale['102'] = "Szavaz�shoz be kell jelentkezni";
$locale['103'] = "Szavazat";
$locale['104'] = "Szavazat";
$locale['105'] = "Szavazatok: ";
$locale['106'] = "Indult: ";
$locale['107'] = "Lez�rva: ";
$locale['108'] = "Arch�vum";
$locale['109'] = "V�lassz egy szavaz�st a list�b�l:";
$locale['110'] = "Megtekint�s";
$locale['111'] = "Szavaz�s megtekint�se";
// �zen�fal
$locale['120'] = "�zen�fal";
$locale['121'] = "N�v:";
$locale['122'] = "�zenet:";
$locale['123'] = "Elk�ld";
$locale['124'] = "Seg�ts�g";
$locale['125'] = "�zenet k�ld�s�hez be kell jelentkezni";
$locale['126'] = "Arch�vum";
$locale['127'] = "M�g nem k�ldtek �zenetet";
// L�bjegyzet
$locale['140'] = "l�togat�";
$locale['141'] = "l�togat�";
// Admin Navig�ci�
$locale['150'] = "Adminisztr�tori f�men�";
$locale['151'] = "F�oldal";
$locale['152'] = "Adminisztr�ci�";
// Vegyes
$locale['190'] = "Az oldal karbantart�s alatt �ll";
$locale['191'] = "IP c�med a feketelist�n szerepel";
$locale['192'] = "Kijelentkez�s: ";
$locale['193'] = "Bejelentkez�s: ";
$locale['194'] = "A hozz�f�r�sed jelenleg fel van f�ggesztv.";
$locale['195'] = "A hozz�f�r�sed m�g nincs aktiv�lva";
$locale['196'] = "Hib�s felhaszn�l�n�v vagy jelsz�";
$locale['197'] = "�tir�ny�t�s folyamatban...<br><br>
[ <a href='index.php'>Kattints ide, ha nem akarsz v�rni</a> ]";
$locale['198'] = "<b>FIGYELEM:</b> a setup.php �llom�nyt m�g nem t�r�lted, telep�t�s ut�n min�l hamarabb t�r�ld!";
?>