<?php
$locale['400'] = "Regisztr�ci�";
$locale['401'] = "Aktiv�l�s";
// Registration Errors
$locale['402'] = "Meg kell adni a felhaszn�l�nevet, jelsz�t �s e-mail c�met.";
$locale['403'] = "A felhaszn�l�n�v nem enged�lyezett karaktereket tartalmaz.";
$locale['404'] = "A k�t jelsz� nem egyezik meg.";
$locale['405'] = "Rossz jelsz�, csak bet�ket �s sz�mokat haszn�lj.<br>A jelsz�nak legal�bb 6 karakter hossz�nak kell lennie.";
$locale['406'] = "Az e-mail c�m �rv�nytelennek t�nik.";
$locale['407'] = "".(isset($_POST['username']) ? $_POST['username'] : "")." felhaszn�l�n�v haszn�latban van.";
$locale['408'] = "".(isset($_POST['email']) ? $_POST['email'] : "")." e-mail c�m m�r haszn�latban van.";
$locale['409'] = "Ezzel az e-mail c�mmel m�r van egy nem aktiv�lt felhaszn�l�.";
$locale['410'] = "Helytelen ellen�rz� k�d.";
$locale['411'] = "Az e-mail c�med, vagy az e-mail domain r�sze feketelist�n van.";
// Email Message
$locale['449'] = "�dv�zl�nk - ".$settings['sitename'];
$locale['450'] = "Szia ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
�dv�zl�nk oldalunkon - ".$settings['sitename'].". 
Felhaszn�l�i azonos�t�id:\n
Felhaszn�l�n�v: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Jelsz�: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
A k�vetkez� linken aktiv�lhatod hozz�f�r�sed:\n";
// Registration Success/Fail
$locale['451'] = "Regisztr�ci� befejezve";
$locale['452'] = "Most m�r be tudsz jelentkezni.";
$locale['453'] = "Egy adminisztr�tor hamarosan aktiv�lja hozz�f�r�sed.";
$locale['454'] = "A regisztr�ci� m�r majdnem k�sz.<br> Hamarosan kapsz egy aktiv�l� e-mail-t a felhaszn�l�neveddel, jelszavaddal �s egy linkkel, amin aktiv�lhatod magad.";
$locale['455'] = "Hozz�f�r�sed aktiv�lva.";
$locale['456'] = "Regisztr�ci� sikertelen";
$locale['457'] = "Nem siker�lt elk�ldeni a levelet. K�rlek l�pj kapcsolatba az oldal <a href='mailto:".$settings['siteemail']."'>Adminisztr�tor�val</a>";
$locale['458'] = "Regisztr�ci� sikertelen a k�vetkez� okok miatt:";
$locale['459'] = "Pr�b�ld �jra";
// Register Form
$locale['500'] = "Add meg a regisztr�ci�hoz sz�ks�ges adatokat. ";
$locale['501'] = "Egy ellen�rz� levelet k�ldt�nk az �ltalad megadott e-mail c�mre. ";
$locale['502'] = "A <span style='color:#ff0000;'>*</span>-gal megjel�lt mez�ket k�telez� kit�lteni
A rendszer megk�l�nb�zteti a kis- �s nagybet�ket.";
$locale['503'] = " Bel�p�s ut�n szerkesztheted �s b�v�theted az adataidat.";
$locale['504'] = "Ellen�rz� k�d:";
$locale['505'] = "�rd be az ellen�rz� k�dot:";
$locale['506'] = "Regisztr�lok";
$locale['507'] = "A regisztr�ci� sz�netel";
// Validation Errors
$locale['550'] = "Add meg a felhaszn�l�nevet!";
$locale['551'] = "Add meg a jelsz�t!";
$locale['552'] = "Add meg az e-mail c�med!";
?>