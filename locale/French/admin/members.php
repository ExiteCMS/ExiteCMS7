<?php
// Member Management Options
$locale['400'] = "Membres";
$locale['401'] = "Utilisateur";
$locale['402'] = "Ajouter";
$locale['403'] = "Type d'utilisateur";
$locale['404'] = "Options";
$locale['405'] = "Voir";
$locale['406'] = "Editer";
$locale['407'] = "Enlever de la Liste Noire";
$locale['408'] = "Bannir";
$locale['409'] = "Supprimer";
$locale['410'] = "Aucun nom d'utilisateur ne commence par ";
$locale['411'] = "Voir tous les membres";
$locale['412'] = "Activer";
// Ban/Unban/Delete Member
$locale['420'] = "Bannissement Effectu�";
$locale['421'] = "Bannissement Supprim�";
$locale['422'] = "Membre supprim�";
$locale['423'] = "Etes vous s�r de vouloir supprimer ce membre ?";
$locale['424'] = "Membre Activ�";
$locale['425'] = "Account activated at ";
$locale['426'] = "Bonjour [USER_NAME],\n
Votre compte sur ".$settings['sitename']." � �t� activ�.\n
Vous pouvez maintenant vous connecter en utilisant votre nom d'utilisateur et votre mot de passe.\n
Merci,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editer un membre";
$locale['431'] = "D�tails du membre modifi�s";
$locale['432'] = "Retour � l'administration des membres";
$locale['433'] = "Retour � l'administration du site";
$locale['434'] = "Impossible d'effectuer les modifications:";
// Extra Edit Member Details form options
$locale['440'] = "Sauvegarder les modifications";
// Update Profile Errors
$locale['450'] = "Edition du Super Administrateur Impossible.";
$locale['451'] = "Vous devez entrer un nom d'utilisateur & une adresse Email.";
$locale['452'] = "Le nom d'utilisateur contient des caract�res invalides.";
$locale['453'] = "Le nom d'utilisteur ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." est d�j� utilis�.";
$locale['454'] = "Adresse Email Invalide.";
$locale['455'] = "Adresse Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." d�j� utilis�e.";
$locale['456'] = "Nouveaux Mots de Passe introuvables.";
$locale['457'] = "Mot de passe invalide, utilisez des caract�res alphanum�riques seulement.<br>
Le mot de passe doit contenir au moins 6 caract�res.";
$locale['458'] = "<b>Attention:</b> ex�cution d'un script innatendue.";
// View Member Profile
$locale['470'] = "Profil du membre: ";
$locale['472'] = "Statistiques";
$locale['473'] = "Groupes d'utilisateurs";
// Add Member Errors
$locale['480'] = "Ajouter";
$locale['481'] = "Compte cr��.";
$locale['482'] = "Cr�ation du compte impossible.";
?>
