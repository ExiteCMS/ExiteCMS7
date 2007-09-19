<?php
// Member Management Options
$locale['400'] = "�yeler";
$locale['401'] = "�ye";
$locale['402'] = "Ekle";
$locale['403'] = "�ye Tipi";
$locale['404'] = "Se�enekler";
$locale['405'] = "G�r�n�m";
$locale['406'] = "D�zenle";
$locale['407'] = "Ban� Kald�r";
$locale['408'] = "Banla";
$locale['409'] = "Sil";
$locale['410'] = "Arad���n�z kriterlere g�re �ye bulunamad�.";
$locale['411'] = "Hepsini G�ster";
$locale['412'] = "Aktivite Et";
// Ban/Unban/Delete Member
$locale['420'] = "Siteden Banla";
$locale['421'] = "Ban� Kald�r";
$locale['422'] = "�ye Silindi";
$locale['423'] = "Bu �yeyi silmek istedi�inizden eminmisiniz?";
$locale['424'] = "�ye Aktivite Edildi";
$locale['425'] = "Aktivite edilen hesap ";
$locale['426'] = "Merhaba [USER_NAME],\n
".$settings['sitename']." sitesindeki �yelik hesab�n�z aktivite edildi.\n
Kullan�c� Ad�n�z ve �ifrenizle sitemize giri� yapabilirsiniz.\n
Te�ekk�rler,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Kullan�c� D�zenle";
$locale['431'] = "Kullan�c� Bilgileri G�ncellendi";
$locale['432'] = "�ye Y�netimine Geri D�n";
$locale['433'] = "Site Y�netimine Geri D�n";
$locale['434'] = "G�ncellenemeyen Kullan�c� Bilgileri:";
// Extra Edit Member Details form options
$locale['440'] = "De�i�iklikleri Kaydet";
// Update Profile Errors
$locale['450'] = "Site Sahibi olan ve �lk Admin Olarak tan�mlanan y�netici d�zenlenemez.";
$locale['451'] = "Bir �ye ismi ve e-mail adresi belirtmelisiniz.";
$locale['452'] = "�ye isminde ge�ersiz karakter var.";
$locale['453'] = "Bu kullan�c� ad� ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." daha �nce al�nm��.";
$locale['454'] = "Ge�ersiz e-mail adresi.";
$locale['455'] = "Bu e-mail adresi ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." daha �nce al�nm��.";
$locale['456'] = "�ifreler birbirini tutmuyor.";
$locale['457'] = "Ge�ersiz �ifre, T�rk�e Karakter Kullanmay�n�z.<br>
�ifreniz minimum 6 karakter uzunlu�unda olmal�d�r.";
$locale['458'] = "<b>Dikkat:</b> belirlenemeyen bir script hatas� olu�tu.";
// View Member Profile
$locale['470'] = "�ye Profili: ";
$locale['471'] = "Genel Bilgiler";
$locale['472'] = "�statistik";
$locale['473'] = "Kullan�c� Gruplar�";
// Add Member Errors
$locale['480'] = "�ye Ekle";
$locale['481'] = "�ye Hesab� Olu�turuldu.";
$locale['482'] = "�ye Hesab� Olu�turulam�yor.";
?>