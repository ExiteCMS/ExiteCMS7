<?php
/*
Turkish Language Fileset
MAxwELL_TR - Barzo (phpfusion-tr)
Email: webmaster@phpfusion-tr.com
Web: http://www.phpfusion-tr.com
*/

// Locale Settings
setlocale(LC_TIME, "tr","TR"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-9";
$locale['tinymce'] = "tr";
$locale['phpmailer'] = "tr";

// Full & Short Months
$locale['months'] = "&nbsp|Ocak|�ubat|Mart|Nisan|May�s|Haziran|Temmuz|A�ustos|Eyl�l|Ekim|Kas�m|Aral�k";
$locale['shortmonths'] = "&nbsp|Ock|�bt|Mar|Nis|May|Haz|Tem|Agus|Eki|Eyl|Kas|Arlk";

// Standard User Levels
$locale['user0'] = "Genel";
$locale['user1'] = "�ye";
$locale['user2'] = "Y�netici";
$locale['user3'] = "S�per Y�netici";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Ana Men�";
$locale['002'] = "Kay�tl� Link Yok\n";
$locale['003'] = "Sadece �yeler";
$locale['004'] = "Bu paneli sadece �yelere g�r�nt�leyebilir yada hen�z bir i�erik eklenmemi� olabilir.";
// Users Online
$locale['010'] = "�evrimi�i Y�neticiler";
$locale['011'] = "�evrimi�i Ziyaret�iler: ";
$locale['012'] = "�evrimi�i Y�netici: ";
$locale['013'] = "�evrimi�i Y�netici Yok";
$locale['014'] = "Kay�tl� Y�neticiler: ";
$locale['015'] = "Aktivite Edilmemi� Y�neticiler: ";
$locale['016'] = "Aktif Y�netici: ";
// Sidebar
$locale['020'] = "Forum Ba�l�klar�";
$locale['021'] = "En Yeni Ba�l�klar";
$locale['022'] = "En Fazla �lgilenilen Ba�l�klar";
$locale['023'] = "En Son �nceleme";
$locale['024'] = "Ho�geldiniz";
$locale['025'] = "Enson Aktif Forum Ba�l�klar�";
$locale['026'] = "Yeni Ba�l�klar�m";
$locale['027'] = "Yeni Forum Mesajlar�m";
$locale['028'] = "Yeni Mesajlar";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Ba�l�klar";
$locale['032'] = "G�r�nt�lenme";
$locale['033'] = "Cevaplar";
$locale['034'] = "Enson Forum Mesaj�";
$locale['035'] = "Konu";
$locale['036'] = "G�nderen";
$locale['037'] = "Hen�z yazm�� oldu�unuz hi� forum ba�l���n�z yok.";
$locale['038'] = "Hen�z yazm�� oldu�unuz hi� forum mesaj�n�z yok.";
$locale['039'] = "Son geli�inizden bu yana %u yeni forum mesaj� var.";
// News & Articles
$locale['040'] = "Yazar ";
$locale['041'] = "- ";
$locale['042'] = "Devam�";
$locale['043'] = " Yorumlar";
$locale['044'] = " Okuma";
$locale['045'] = "Yazd�r";
$locale['046'] = "Haber Yok";
$locale['047'] = "Hen�z haber g�nderilmemi�.";
$locale['048'] = "D�zenle";
// Prev-Next Bar
$locale['050'] = "�nceki";
$locale['051'] = "Sonraki";
$locale['052'] = "Sayfa ";
$locale['053'] = " - ";
// User Menu
$locale['060'] = "Y�netici Giri�i";
$locale['061'] = "Kullan�c� Ad�";
$locale['062'] = "�ifre";
$locale['063'] = "Beni Hat�rla";
$locale['064'] = "Giri�";
$locale['065'] = "Hen�z �YE Olmad�n�z m�?<br><a href='".BASEDIR."register.php' class='side'>Buraya T�klayarak</a> �ye Olabilirsiniz.";
$locale['066'] = "�ifremi Unuttum?<br>�ifrenizi ��renebilmek i�in <a href='".BASEDIR."lostpassword.php' class='side'>Buraya T�klay�n</a>.";
//
$locale['080'] = "Profil D�zenle";
$locale['081'] = "�zel Mesajlar";
$locale['082'] = "�ye Listesi";
$locale['083'] = "Y�netici Paneli";
$locale['084'] = "��k��";
$locale['085'] = "%u Yeni ";
$locale['086'] = "mesaj";
$locale['087'] = "mesajlar";
// Poll
$locale['100'] = "Anket";
$locale['101'] = "Oy Ver";
$locale['102'] = "Ankete kat�labilmek i�in �ye olman�z yada �ye giri�i yapman�z gerekmektedir.";
$locale['103'] = "Oy";
$locale['104'] = "Oylar";
$locale['105'] = "Oylar: ";
$locale['106'] = "Ba�lang��: ";
$locale['107'] = "Biti�: ";
$locale['108'] = "Anket Ar�ivi";
$locale['109'] = "Listeden bir anket se�iniz:";
$locale['110'] = "G�ster";
$locale['111'] = "Anket G�ster";
// Shoutbox
$locale['120'] = "K�sa Mesajlar";
$locale['121'] = "�sim:";
$locale['122'] = "Mesaj�n�z:";
$locale['123'] = "G�nder";
$locale['124'] = "Yard�m";
$locale['125'] = "Mesaj�n�z� g�nderebilmeniz i�in �ye olman�z yada �ye giri�i yapman�z gerekmektedir.";
$locale['126'] = "K�sa Mesajlar Ar�ivi";
$locale['127'] = "Hen�z Mesaj G�nderilmemi�.";
// Footer Counter
$locale['140'] = "Ziyaret�i";
$locale['141'] = "Tekil Ziyaret�iler";
// Admin Navigation
$locale['150'] = "Admin Paneli";
$locale['151'] = "Siteye Geri D�n";
$locale['152'] = "Admin Panelleri";
// Miscellaneous
$locale['190'] = "Site Bak�m Modunu Aktif Et";
$locale['191'] = "IP Adresiniz Kara Listeye Al�nm��t�r.";
$locale['192'] = "Sitemizden ��k�� Yapan �ye ";
$locale['193'] = "Sitemize Giri� Yapan �ye ";
$locale['194'] = "�ye Hesab�n�z Ask�ya Al�nm��t�r.";
$locale['195'] = "Bu �ye Hesab� Aktivite Edilmemi�.";
$locale['196'] = "Ge�ersiz Kullan�c� Ad� yada �ifre.";
$locale['197'] = "L�tfen bekleyin ana sayfaya y�nlendiriliyorsunuz....<br><br>
[ <a href='index.php'>E�er sayfaya y�nlendirilmiyorsan�z l�tfen buraya t�klay�n</a> ]";
$locale['198'] = "<b>Dikkat : </b> setup.php dosyas� bulundu. L�tfen Siliniz";
?>