<?php
$locale['400'] = "Registracija";
$locale['401'] = "Aktyvuoti prisijungim�";
// Registration Errors
$locale['402'] = "Turite �ra�yti vartotojo vard�, slapta�od�, bei e-pa�to adres�.";
$locale['403'] = "Vartotojo varde yra neleistin� simboli�.";
$locale['404'] = "Abu slapta�od�iai nesutampa.";
$locale['405'] = "Neteisingas slapta�odis, naudokite tik raides ir skai�ius.<br>
Slapta�od� turi sudaryti ma�iausiai 6 simboliai.";
$locale['406'] = "J�s� e-pa�to adresas negaliojantis.";
$locale['407'] = "Atleiskit, toks vardas ".(isset($_POST['username']) ? $_POST['username'] : "")." jau naudojamas.";
$locale['408'] = "Atleiskit toks e-pa�to adresas ".(isset($_POST['email']) ? $_POST['email'] : "")." jau naudojamas.";
$locale['409'] = "�iuo e-pa�to adresu prisiregistravo neaktyvus vartotojas.";
$locale['410'] = "Neteisingas patvirtinimo kodas.";
$locale['411'] = "J�s� e-pa�to adresas arba e-pa�to domenas yra �trauktas � juod�j� s�ra��.";
// Email Message
$locale['449'] = "Sveiki prisijung� prie ".$settings['sitename'];
$locale['450'] = "Sveiki ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
atvyk� � ".$settings['sitename'].". J�s� prisijungimo duomenys:\n
Vartotojo vardas: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Slapta�odis: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktyvuokite savo prisijungim� paspausdami ant �ios nuorodos:\n";
// Registration Success/Fail
$locale['451'] = "Registracija baigta";
$locale['452'] = "Dabar galite prisijungti.";
$locale['453'] = "Administratorius per trump� laik� aktyvuos j�s� prisijungim�.";
$locale['454'] = "J�s� registracija beveik baigta, dabar j�s gausite e-pa�to �inut� su prisijungimo duomenimis, bei nuoroda, kuri� paspaud�, aktyvuosite savo prisijungim�.";
$locale['455'] = "J�s� prisijungimas patvirtintas.";
$locale['456'] = "Registracija nutraukta";
$locale['457'] = "�vyko klaida, siun�iant e-pa�t�, pra�ome susisiekti su <a href='mailto:".$settings['siteemail']."'>tinklapio administracija</a>.";
$locale['458'] = "Registracija nutraukta d�l sekan�ios(i�) prie�asties(�i�):";
$locale['459'] = "Bandykite dar kart�!";
// Register Form
$locale['500'] = "Pra�ome �vesti j�s� duomenis. ";
$locale['501'] = "Patvirtinimas pa�tu bus nusi�stas �iuo pa�to adresu, kur� �ia nurod�te. ";
$locale['502'] = "Pa�ym�ti laukeliai <span style='color:#ff0000;'>*</span> turi b�ti u�pildyti.
J�s� vartotojo vardas ir slapta�odis yra labai svarb�s.";
$locale['503'] = " Papildom� informacij� gal�site prid�ti, kai prisijungsite ir nueisite � panel� <b>Redaguoti apra�ym�.</b>";
$locale['504'] = "Patvirtinimo kodas:";
$locale['505'] = "�veskite patvirtinimo kod�:";
$locale['506'] = "Registruotis";
$locale['507'] = "Registracijos sistema laikinai i�jungta.";
// Validation Errors
$locale['550'] = "Pra�om �vesti vartotojo vard�.";
$locale['551'] = "Pra�om �vesti slapta�od�.";
$locale['552'] = "Pra�om �vesti e-pa�to adres�.";
?>