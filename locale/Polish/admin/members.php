<?php
// Member Management Options
$locale['400'] = "U�ytkownicy";
$locale['401'] = "U�ytkownik";
$locale['402'] = "Dodaj";
$locale['403'] = "Typ U�ytkownika";
$locale['404'] = "Opcje";
$locale['405'] = "Zobacz";
$locale['406'] = "Edytuj";
$locale['407'] = "Odbanuj";
$locale['408'] = "Zbanuj";
$locale['409'] = "Usu�";
$locale['410'] = "Nie ma u�ytkownik�w, kt�rych nicki rozpoczynaj� si� na ";
$locale['411'] = "Poka� Wszystkich";
$locale['412'] = "Aktywuj";
// Ban/Unban/Delete Member
$locale['420'] = "Zbanowano";
$locale['421'] = "Odbanowano";
$locale['422'] = "U�ytkownik Usuni�ty";
$locale['423'] = "Jeste� pewny, �e chcesz usun�� tego u�ytkownika?";
$locale['424'] = "U�ytkownik Aktywowany";
$locale['425'] = "Konto aktywowano ";
$locale['426'] = "Witaj [USER_NAME],\n
Twoje konto w witrynie ".$settings['sitename']." zosta�o aktywowane.\n
Mo�esz si� teraz zalogowa� korzystaj�c ze swojej nazwy u�ytkownika i has�a.\n
Pozdrawiam,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Edytuj U�ytkownika";
$locale['431'] = "Konto U�ytkownika Zosta�o Zaktualizowane";
$locale['432'] = "Powr�t do Zarz�dzania U�ytkownikami";
$locale['433'] = "Powr�t do Panelu Admina";
$locale['434'] = "Profil U�ytkownika nie zosta� zaktualizowany:";
// Extra Edit Member Details form options
$locale['440'] = "Zapisz Zmiany";
// Update Profile Errors
$locale['450'] = "Nie mo�esz edytowa� konta G��wnego Admina.";
$locale['451'] = "Musisz poda� nazw� U�ytkownika i adres e-mail.";
$locale['452'] = "Nazwa U�ytkownika [nick] zawiera niedozwolone znaki.";
$locale['453'] = "Nick ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." jest ju� przez kogo� u�ywany.";
$locale['454'] = "Nieprawid�owy adres e-mail.";
$locale['455'] = "Adres e-mail ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." jest ju� przez kogo� u�ywany.";
$locale['456'] = "Oba has�a musz� by� identyczne!";
$locale['457'] = "Nieprawid�owe has�o - mo�esz u�ywa� tylko liter i cyfr.<br>
Has�o musi mie� przynajmniej 6 znak�w d�ugo�ci";
$locale['458'] = "<b>UWAGA:</b> nieoczekiwany b��d podczas wykonywania skryptu.";
// View Member Profile
$locale['470'] = "Profil U�ytkownika";
$locale['472'] = "Statystyki";
$locale['473'] = "Grupy U�ytkownik�w";
// Add Member Errors
$locale['480'] = "Dodaj U�ytkownika";
$locale['481'] = "Konto U�ytkownika zosta�o utworzone.";
$locale['482'] = "Konto nie zosta�o utworzone.";
?>
