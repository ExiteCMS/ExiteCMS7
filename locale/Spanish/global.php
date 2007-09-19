<?php
/*
Spanish Language Fileset
Produced by Gonzalo Suez (Belial)
Email: info@gsuez.cl
Web: http://www.gsuez.cl

Collaborator: Sigstorm
Email: sigstorm@gmail.com
*/

// Locale Settings
setlocale(LC_TIME, "es","ES"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "es";
$locale['phpmailer'] = "es";

// Full & Short Months
setlocale(LC_ALL, 'es_ES' );
$locale['months'] = "&nbsp|Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre";
$locale['shortmonths'] = "&nbsp|Ene|Feb|Mar|Abr|May|Jun|Jul|Ago|Sept|Oct|Nov|Dic";

// Standard User Levels
$locale['user0'] = "P�blico";
$locale['user1'] = "Miembro";
$locale['user2'] = "Administrador";
$locale['user3'] = "Super Administrador";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderador";
// Navigation
$locale['001'] = "Navegaci�n";
$locale['002'] = "No hay enlaces definidos";
$locale['003'] = "S�lo Usuarios";
$locale['004'] = "No hay contenido para este panel a�n";
// Users Online
$locale['010'] = "En l�nea";
$locale['011'] = "Invitados: ";
$locale['012'] = "Usuarios: ";
$locale['013'] = "No hay usuarios en l�nea";
$locale['014'] = "Usuarios Registrados: ";
$locale['015'] = "Usuarios Inactivos: ";
$locale['016'] = "Nuevos: ";
// Sidebar & Other Titles
$locale['020'] = "Temas del Foro";
$locale['021'] = "Nuevos Temas";
$locale['022'] = "Temas m�s Populares";
$locale['023'] = "�ltimos Art�culos";
$locale['024'] = "Bienvenido";
$locale['025'] = "�ltimos Temas de Foros Activos";
$locale['026'] = "Temas Recientes";
$locale['027'] = "Mensajes Recientes ";
$locale['028'] = "Nuevos Env�os";
// Forum List Texts
$locale['030'] = "Foros";
$locale['031'] = "Temas";
$locale['032'] = "Vistas";
$locale['033'] = "Respuestas";
$locale['034'] = "Ultimos Env�os";
$locale['035'] = "Asunto";
$locale['036'] = "Enviado";
$locale['037'] = "Usted no ha empezado ning�n tema de foro todav�a.";
$locale['038'] = "Usted no ha enviado ning�n mensaje al foro todav�a.";
$locale['039'] = "Hay %u nuevos envi�s desde su �ltima visita.";
// News & Articles
$locale['040'] = "Enviado por ";
$locale['041'] = "en ";
$locale['042'] = "Leer M�s";
$locale['043'] = " Comentarios";
$locale['044'] = " Leer";
$locale['045'] = "Imprimir";
$locale['046'] = "Noticias";
$locale['047'] = "Ninguna Noticias ha sido enviada todav�a";
$locale['048'] = "Editar";
// Prev-Next Bar
$locale['050'] = "Anterior";
$locale['051'] = "Pr�ximo";
$locale['052'] = "P�gina ";
$locale['053'] = " de ";
// User Menu
$locale['060'] = "Registro";
$locale['061'] = "Usuario";
$locale['062'] = "Contrase�a";
$locale['063'] = "Recordarme";
$locale['064'] = "Ingresar";
$locale['065'] = "�A�n no es Usuario?<br><a href='".BASEDIR."register.php' class='side'>Click aqu�</a> para registrarser.";
$locale['066'] = "�Olvid� su contrase�a?<br>Pedir una nueva <a href='".BASEDIR."lostpassword.php' class='side'>aqu�</a>.";
//
$locale['080'] = "Editar Perfil";
$locale['081'] = "Mensajes Privados";
$locale['082'] = "Lista de Usuarios";
$locale['083'] = "Administraci�n";
$locale['084'] = "Cerrar Sesi�n";
$locale['085'] = "Tienes %u nuevo";
$locale['086'] = "mensaje";
$locale['087'] = "mensajes";
// Poll
$locale['100'] = "Votaci�n";
$locale['101'] = "Votar";
$locale['102'] = "Debe registrarse para votar.";
$locale['103'] = "Voto";
$locale['104'] = "Votos";
$locale['105'] = "Votos: ";
$locale['106'] = "Comenz�: ";
$locale['107'] = "Finaliz�: ";
$locale['108'] = "Archivo de Encuestas";
$locale['109'] = "Seleccione una encuesta de la lista:";
$locale['110'] = "Ver";
$locale['111'] = "Ver Encuesta";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Nombre:";
$locale['122'] = "Mensaje:";
$locale['123'] = "Shout";
$locale['124'] = "Ayuda";
$locale['125'] = "Debe ser usuario para enviar un mensaje.";
$locale['126'] = "Shoutbox Archivo";
$locale['127'] = "Ning�n mensaje ha sido enviado.";
// Footer Counter
$locale['140'] = "Visita Unica";
$locale['141'] = "Visitas Unicas";
// Admin Navigation
$locale['150'] = "Inicio de Administraci�n";
$locale['151'] = "Volver al Sitio";
$locale['152'] = "Paneles de Administraci�n";
// Miscellaneous
$locale['190'] = "Modo Mantenci�n Activado";
$locale['191'] = "Su direcci�n IP ha sido puesta en la lista negra.";
$locale['192'] = "Salir como ";
$locale['193'] = "Entrar como ";
$locale['194'] = "Esta cuenta est� actualmente suspendida.";
$locale['195'] = "Esta cuenta no ha sido activada.";
$locale['196'] = "Nombre de usuario o contrase�a incorrectos.";
$locale['197'] = "Por favor espere mientras lo transferimos...<br><br>
[ <a href='index.php'>o haga click aqu� si no desea esperar</a> ]";
$locale['198'] = "<b>Advertencia:</b> setup.php detectado, por favor b�rrelo inmediatamente";
?>