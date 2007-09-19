<?php
$locale['400'] = "Registro";
$locale['401'] = "Activar Cuenta";
// Registration Errors
$locale['402'] = "Usted debe especificar un nombre de usuario, contrase�a y la direcci�n de email.";
$locale['403'] = "El nombre del usuario contiene caracteres incorrectos.";
$locale['404'] = "Las dos contrase�as no coinciden.";
$locale['405'] = "Contrase�a incorrecta, s�lo use caracteres alfanum�ricos.<br>
La contrase�a debe tener un m�nimo de 6 caracteres.";
$locale['406'] = "Su direcci�n de email no parece ser v�lida.";
$locale['407'] = "Lo sentimos, el nombre de usuario ".(isset($_POST['username']) ? $_POST['username'] : "")." est� en uso.";
$locale['408'] = "Lo sentimos, la direcci�n de email ".(isset($_POST['email']) ? $_POST['email'] : "")." est� en uso.";
$locale['409'] = "Una cuenta inactiva ha sido registrada con la direcci�n de email.";
$locale['410'] = "C�digo de aprobaci�n incorrecto.";
$locale['411'] = "Su direcci�n de email o su dominio esta en la lista negra.";
// Email Message
$locale['449'] = "Bienvenido a ".$settings['sitename'];
$locale['450'] = "Hola ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bienvenido a ".$settings['sitename'].". Aqu� estan los detalles de su ingreso:\n
Usuario: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Contrase�a: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor active su cuenta pulsando el siguiente link:\n";
// Registration Success/Fail
$locale['451'] = "Registro completo";
$locale['452'] = "Ahora puede ingresar.";
$locale['453'] = "Un administrador activar� su cuenta.";
$locale['454'] = "Su registro est� casi completo, usted recibir� un email que contiene los detalles de la inscripci�n junto con un link para verificar su cuenta.";
$locale['455'] = "Su cuenta a sido verificada.";
$locale['456'] = "El Registro fall�";
$locale['457'] = "El env�o de email fall�, contacte al <a href='mailto:".$settings['siteemail']."'>Administrador</a>.";
$locale['458'] = "El registro fall� por la(s) siguiente(s) raz�n(es):";
$locale['459'] = "Por favor int�ntelo otra vez";
// Register Form
$locale['500'] = "Ingrese los detalles solicitados abajo. ";
$locale['501'] = "Un email del verificaci�n se enviar�n a la direcci�n especificada. ";
$locale['502'] = "Los campos marcados <span style='color:#ff0000;'>*</span> son obligatorios.
Los nombres de usuario y contrase�a son sensibles a may�sculas y min�sculas.";
$locale['503'] = " Puede ingresar informaci�n adicional en <b>Editar Perfil</b> una vez Inscrito.";
$locale['504'] = "C�digo de Validaci�n:";
$locale['505'] = "Ingrese C�digo de Validaci�n:";
$locale['506'] = "Registro";
$locale['507'] = "El sistema del registro est� actualmente desactivado.";
// Validation Errors
$locale['550'] = "Especifique un nombre de usuario.";
$locale['551'] = "Especifique una contrase�a.";
$locale['552'] = "Especifique una direcci�n de email.";
?>