<?php
$locale['400'] = "�ye Kay�t";
$locale['401'] = "�ye Hesab� Olu�turma";
// Registration Errors
$locale['402'] = "Kay�t i�in; Kullan�c� ad�, �ifre ve E-mail adresinizi giriniz.";
$locale['403'] = "Kullan�c� ad�n�zda T�rk�e karakter kullanmay�n�z.";
$locale['404'] = "Yazd���n�z iki �ifre birbirini tutmuyor.";
$locale['405'] = "Ge�ersiz �ifre. Sadece rakam ve T�rk�e karakter haricinde harflerden olu�abilir.<br>
�ifreniz en az 6 haneli olmal�d�r.";
$locale['406'] = "Ge�ersiz e-mail adresi girdiniz l�tfen tekrar kontrol ediniz.";
$locale['407'] = "�zg�n�m, ".(isset($_POST['username']) ? $_POST['username'] : "")." kullan�c� ad� daha �nce al�nm��.";
$locale['408'] = "�zg�n�m, kay�t i�in girdi�iniz ".(isset($_POST['email']) ? $_POST['email'] : "")." e-mail adresi daha �nce kullan�lm��.";
$locale['409'] = "Bu e-mail adresi aktif edilmemi� bir kullan�c� hesab�na aittir.";
$locale['410'] = "Yanl�� G�venlik Kodu.";
$locale['411'] = "E-mail adresiniz yada domaininiz kara listeye al�nm��.";
// Email Message
$locale['450'] = "Merhaba ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
".$settings['sitename'].".Sitesine Ho�geldiniz. Kullan�c� hesab�n�za ait bilgiler a�a��daki gibidir:\n
Kullan�c� Ad�: ".(isset($_POST['username']) ? $_POST['username'] : "")."
�ifre: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
�yeli�inizi tamamlay�p aktif hale getirebilmek i�in l�tfen a�a��daki linke t�klay�n�z:\n";
// Registration Success/Fail
$locale['451'] = "Kayd�n�z ba�ar�yla tamamland�.";
$locale['452'] = "Siteye giri� yapabilirsiniz.";
$locale['453'] = "Kayd�n�z tamamland�, Birka� dakika i�inde site y�neticisinden bir e-mail alacaks�n�z. Gelen e-mail de size verilen aktivasyon linkini t�klad�ktan sonra siteye giri� yapabilirsiniz.";
$locale['454'] = "Kullan�c� hasab�n�z� aktif hale getirebilmek i�in, vermi� oldu�unuz e-mail adresine bir aktivasyon maili alacaks�n�z orada size belirtilen linki t�klayarak hesab�n�z� aktif hale getirip siteye giri� yapabilirsiniz. Aktivasyon e-maili 1 g�n i�inde gelecektir. Bu 1 g�n i�inde �yeli�ini aktif etmeyenleri sistem otomatik olarak S�LECEKT�R bilginize..!";
$locale['455'] = "�yelik kayd�n�z tamamland�.";
$locale['456'] = "Kay�t Hatas�";
$locale['457'] = "Sistem mail g�nderemedi, l�tfen <a href='mailto:".$settings['siteemail']."'>Site Sahibi yada Y�neticileri</a> ile irtibat ge�iniz.";
$locale['458'] = "A�a��daki cebep yada sebeplerden dolay� bir hata olu�tu:";
$locale['459'] = "L�tfen Tekrar Deneyin";
// Register Form
$locale['500'] = "L�tfen a�a��daki alanlar� doldurunuz. �yelik �CRETS�ZD�R..! ";
$locale['501'] = "Kay�t esnas�nda vermi� oldu�unuz e-mail adresine aktivasyon kodu g�nderilecektir. ";
$locale['502'] = "Doldurulmas� zorunlu alanlar <span style='color:#ff0000;'>*</span> (y�ld�z) ile belirtilmi�tir.";
$locale['503'] = " Daha sonra �yelik bilgilerinizde de�i�iklik yapmak i�in Profil D�zenle linkini t�klayarak bilgilerinizi g�ncelleyebilirsiniz.";
$locale['504'] = "G�venlik Kodu:";
$locale['505'] = "G�venlik Kodunu Giriniz:";
$locale['506'] = "Kay�t Ol";
$locale['507'] = "�yelik Kayd� Sistemi Dondurulmu�tur.";
// Validation Errors
$locale['550'] = "L�tfen Kullan�c� Ad� Giriniz.";
$locale['551'] = "L�tfen �ifre Giriniz.";
$locale['552'] = "L�tfen E-mail Adresi Giriniz.";
?>