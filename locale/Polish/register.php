<?php
$locale['400'] = "Rejestracja";
$locale['401'] = "Aktywuj Konto";
// Registration Errors
$locale['402'] = "Musisz poda� nazw� u�ytkownika, has�o i email.";
$locale['403'] = "Nazwa U�ytkownika zawiera nieprawid�owe znaki.";
$locale['404'] = "Has�a nie pasuj� do siebie.";
$locale['405'] = "Nieprawid�owe has�o. U�ywaj tylko znak�w alfanumerycznych.<br>
Minimalna d�ugo�� has�a to 6 znak�w.";
$locale['406'] = "Adres email,kt�ry poda�e� jest nieprawid�owy.";
$locale['407'] = "Niestety, ta nazwa u�ytkownika ".(isset($_POST['username']) ? $_POST['username'] : "")." jest ju� u�ywana.";
$locale['408'] = "Niestety, ten adres email ".(isset($_POST['email']) ? $_POST['email'] : "")." jest ju� u�ywany.";
$locale['409'] = "Nieaktywne konto zosta�o zarejestrowane na ten adres email.";
$locale['410'] = "Nieprawid�owy kod potwierdzaj�cy.";
$locale['411'] = "Tw�j adres email lub jego domena s� na naszej Czarnej Li�cie.";
// Email Message
$locale['449'] = "Witaj na stronie ".$settings['sitename'];
$locale['450'] = "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witamy w serwisie ".$settings['sitename'].". Oto Twoje dane potrzebne do zalogowania si� na naszej stronie:\n
Nazwa U�ytkownika - Nick: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has�o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Mo�esz aktywowa� swoje konto klikaj�c na poni�szy odno�nik:\n";
// Registration Success/Fail
$locale['451'] = "Rejestracja zako�czona pomy�lnie";
$locale['452'] = "Teraz mo�esz si� zalogowa�.";
$locale['453'] = "Wkr�tce Twoje konto zostanie aktywowane przez Administratora.";
$locale['454'] = "Twoja rejestracja jest ju� zako�czona, za chwil� otrzymasz email zawieraj�cy Twoje dane wraz z linkiem zwrotnym, aktywuj�cym konto.";
$locale['455'] = "Twoje konto zosta�o zweryfikowane.";
$locale['456'] = "Rejestracja nie powiod�a si�";
$locale['457'] = "Wys�anie listu z Twoimi danymi nie powiod�o si�, prosimy skontaktowa� si� z <a href='mailto:".$settings['siteemail']."'>Administratorem Strony</a>.";
$locale['458'] = "Rejestracja nie powiod�a si� z nast�puj�cych przyczyn:";
$locale['459'] = "Prosimy spr�bowa� ponownie";
// Register Form
$locale['500'] = "Prosimy poda� poni�ej swoje dane. ";
$locale['501'] = "Na podany adres email zostanie wys�any list weryfikacyjny. ";
$locale['502'] = "Pola oznaczone znakiem <span style='color:#ff0000;'>*</span> musz� zosta� wype�nione.
Nazwa U�ytkownika i has�o s� obowi�zkowe.";
$locale['503'] = " Dodatkowe informacje mo�esz wpisa� w p�niejszym czasie, edytuj�c sw�j profil po zalogowaniu si� .";
$locale['504'] = "Kod potwierdzaj�cy:";
$locale['505'] = "Wpisz Kod potwierdzaj�cy:";
$locale['506'] = "Rejestruj";
$locale['507'] = "Aktualnie system rejestracji jest wy��czony. Przepraszamy.";
// Validation Errors
$locale['550'] = "Prosimy poda� Nazw� U�ytkownika.";
$locale['551'] = "Prosimy poda� Has�o.";
$locale['552'] = "Prosimy poda� adres email.";
?>
