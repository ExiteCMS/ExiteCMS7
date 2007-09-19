<?php
$locale['400'] = "Enregistrement";
$locale['401'] = "Compte activ�";
// Registration Errors
$locale['402'] = "Vous devez sp�cifier un pseudo, mot de passe et adresse Email.";
$locale['403'] = "Le pseudo contient des caract�res non valides.";
$locale['404'] = "Vos deux mots de passe ne correspondent pas.";
$locale['405'] = "Mot de passe non valide, utilisez seulement des caract�re alphanum�riques.<br>
Le mot de passe doit �tre d'une longueur minimum de 6 caract�res.";
$locale['406'] = "Votre adresse Email ne semble pas valide.";
$locale['407'] = "D�sol�, le pseudo ".(isset($_POST['username']) ? $_POST['username'] : "")." est d�j� utilis�.";
$locale['408'] = "D�sol�, l'adresse Email ".(isset($_POST['email']) ? $_POST['email'] : "")." est d�j� utilis�e";
$locale['409'] = "Un compte actif a �t� enregistr� avec cette adresse Email.";
$locale['410'] = "Erreur de code de validation.";
$locale['411'] = "Votre adresse Email ou votre nom de Domaine Email est sur liste noire.";
// Email Message
$locale['449'] = "Bienvenue chez ".$settings['sitename'];
$locale['450'] = "Bonjour ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
bienvenue sur ".$settings['sitename'].". Ici vos d�tails de connexion:\n
Pseudo : ".(isset($_POST['username']) ? $_POST['username'] : "")."
Mot de passe: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Veuillez activer votre compte via le lien suivant:\n";
// Registration Success/Fail
$locale['451'] = "Enregistrement complet";
$locale['452'] = "Vous pouvez vous connecter.";
$locale['453'] = "Un administrateur va bient�t activer votre compte.";
$locale['454'] = "Votre enregistrement est presque termin�, vous allez recevoir un Email contenant le d�tail de votre compte et un lien pour la v�rification.";
$locale['455'] = "Votre compte a �t� v�rifi�.";
$locale['456'] = "L'enregistrement a �chou�";
$locale['457'] = "L'envoi du mail a �chou�, veuillez contacter <a href='mailto:".$settings['siteemail']."'>l'Administrateur</a>.";
$locale['458'] = "L'enregistrement � �chou� pour la/les raison(s) suivante(s):";
$locale['459'] = "Veuillez r�essayer";
// Register Form
$locale['500'] = "Veuillez entrer vos informations ci-dessous. ";
$locale['501'] = "Un Email de v�rification vous sera envoy� � l'adresse sp�cifi�e. ";
$locale['502'] = "Les champs marqu�s <span style='color:#ff0000;'>*</span> doivent �tre remplis.
Votre pseudo et mot de passe sont sensibles � la casse(MAJ/MIN).";
$locale['503'] = " Vous pouvez mettre des informations compl�mentaires en �ditant votre profil une fois connect�.";
$locale['504'] = "Code de validation:";
$locale['505'] = "Entrez le code de validation:";
$locale['506'] = "Enregistrer";
$locale['507'] = "L'enregistrement est momentan�ment d�sactiv�.";
// Validation Errors
$locale['550'] = "Veuillez sp�cifier un pseudo.";
$locale['551'] = "Veuillez sp�cifier un mot de passe.";
$locale['552'] = "Veuillez sp�cifier une adresse email.";
?>