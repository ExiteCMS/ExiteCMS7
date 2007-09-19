<?php
// Member Management Options
$locale['400'] = "Usuarios";
$locale['401'] = "Usuario";
$locale['402'] = "Agregar";
$locale['403'] = "Tipo de Usuario";
$locale['404'] = "Opciones";
$locale['405'] = "Ver";
$locale['406'] = "Editar";
$locale['407'] = "UnBan";
$locale['408'] = "Ban";
$locale['409'] = "Borrar";
$locale['410'] = "no hay nombres de usuarios que comienzen con ";
$locale['411'] = "Mostrar Todo";
$locale['412'] = "Activado";
// Ban/Unban/Delete Member
$locale['420'] = "Ban Imposed";
$locale['421'] = "Ban Removido";
$locale['422'] = "Usuario Borrado";
$locale['423'] = "�Esta seguro que quiere borrar a este usuario?";
$locale['424'] = "Usuario Activado";
$locale['425'] = "Cuenta Activada a ";
$locale['426'] = "Hola [USER_NAME],\n
Su cuenta ".$settings['sitename']." ha sido activada.\n
Puede ahora ingresar usando su nombre de usuario y contrase�a.\n
Saludos,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editar Usuario";
$locale['431'] = "Detalles de usuario actualizados";
$locale['432'] = "Regresar a la Administraci�n de Usuarios";
$locale['433'] = "Regresar al Inicio de Administraci�n";
$locale['434'] = "Imposible actualizar detalles de usuario:";
// Extra Edit Member Details form options
$locale['440'] = "Guardar Cambios";
// Update Profile Errors
$locale['450'] = "No puede editar administrador primario.";
$locale['451'] = "Debe especificar un nombre de usuario y direccion email.";
$locale['452'] = "El nombre de usuario contiene caracteres incorrectos.";
$locale['453'] = "El nombre de usuario ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." est� en uso.";
$locale['454'] = "Direcci�n email incorrecta.";
$locale['455'] = "La direcci�n email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." est� en uso.";
$locale['456'] = "Las Nuevas Contrase�as son diferentes.";
$locale['457'] = "Contrase�a incorreuse s�lo caracteres alfanum�ricos.<br>
La contrase�a debe tener como m�nimo 6 caracteres.";
$locale['458'] = "<b>Advertencia:</b> ejecuci�n de escritura inesperado.";
// View Member Profile
$locale['470'] = "Perfil de Usuario";
$locale['472'] = "Estadisticas";
$locale['473'] = "Grupos de Usuarios";
// Add Member Errors
$locale['480'] = "Agregar Usuario";
$locale['481'] = "La cuenta de usuario ha sido creada.";
$locale['482'] = "La cuenta de usuario no pudo ser creada.";
?>