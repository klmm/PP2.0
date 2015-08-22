<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_joueurs.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/lib/mail/envoi_mails.php';
	
	$id_jeu = $_POST["id_jeu"];
	$id_cal = $_POST["id_cal"];
	$objet = $_POST["objet"];
	$contenu = $_POST["contenu"];
	$dest = $_POST["dest"];
	
// --------------- DESTINATAIRES
	
	switch($dest){
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
	    
		case "oubli_premier":
			$joueurs = get_joueurs_oubli_premier_pari($id_jeu, $id_cal);
		break;
	}
	
	print_r($joueurs);

// -----------------------------------
    envoi_mails($joueurs, $objet, $contenu);
?>