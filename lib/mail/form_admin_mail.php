<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/get_joueurs.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/mail/envoi_mails.php';
	
	$id_jeu = $_POST["id_jeu"];
	$id_cal = $_POST["id_cal"];
	$objet = $_POST["objet"];
	$contenu = $_POST["contenu"];
	$dest = $_POST["dest"];
	
// --------------- DESTINATAIRES
	
	/*switch($dest){
		case "tous":			
			$joueurs = get_joueurs_tous();
		break;
		
		case "admins":
			$joueurs = get_joueurs_admins();
		break;
		
		case "jeu":
			$joueurs = get_joueurs_inscrits($id_jeu);
		break;
		
		case "oubli":
			$joueurs = get_joueurs_oubli_paris($id_jeu, $id_cal);
		break;	
	}*/
	
	$joueurs[0][0] = 'Toto_gmail';
	$joueurs[0][1] = 'thomas.cerato@gmail.com';
	$joueurs[1][0] = 'contact_PP';
	$joueurs[1][1] = 'contact@parions-potes.fr';
// -----------------------------------
	envoi_mails($joueurs, $objet, $contenu);
?>