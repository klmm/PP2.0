<?php

	$objet = $_POST["objet"];
	$message = $_POST["contenu"];
	$dest = $_POST["dest"];
	require_once("../sql/joueurs/get_joueurs.php");
	
	switch($dest){
		case "tous":			
			$joueurs = get_joueurs_tous();
		break;
		
		case "admins":
			$joueurs = get_joueurs_admins();
		break;
		
		case "inscrits_jeu":
			echo "inscrits_jeu";
			return;
		break;
		
		case "choisir":
			echo "joueurs au choix";
			return;
		break;
		
	}
	
	foreach ($joueurs as $joueur) {
		$login = $joueur[0];
		$mail = $joueur[1];
		
		echo $login . " - " . $mail;
		//envoi_mail($login, $mail, $objet, $message, $message);
	}

	
	function envoi_mail($login, $mail, $sujet, $message_txt, $message_html)
	{
		//echo $login . $mail . $sujet . $message_txt;
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		
		if ($mail == "-"){
			return;
		}
		
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		//==========
		
		//=====En-tête
		$entete_txt = "Salut " . $login . ", TXT";
		$entete_html = "<html><head></head><body><table border='0' cellspacing='0' cellpadding='0'>";
		$entete_html .= "<tr height='5'><td>Salut " . $login . ",</td></tr>";
		
		
		//=====Signature
		$signature_txt = "Sportivement TXT," . $passage_ligne . $passage_ligne . "L'équipe Parions Potes" . $passage_ligne . "www.parions-potes.fr";
		$signature_html = "<tr height='20'><td>Sportivement,</td></tr>";
		$signature_html .= "<tr height='20'><td>L'équipe Parions Potes</td></tr>";
		$signature_html .= "<tr height='5'><td>www.parions-potes.fr</td></tr>";
		$signature_html .= "</table>";
		
		//=====Ecriture du message avec en-tête, signature
		$message_final_txt = $entete_txt . $passage_ligne . $passage_ligne . $message_txt . $passage_ligne . $passage_ligne . $signature_txt;
		$message_final_html = $entete_html . "<tr height='40'><td>" . $message_html . "</td></tr>" . $signature_html;
		//==========
	
		//=====Création du header de l'e-mail.
		$header = "From: \"Parions Potes\"<parionsp@20gp.ovh.net>".$passage_ligne;
		$header.= "Reply-to: \"Parions Potes\" <contact@parions-potes.fr>".$passage_ligne;
		$header.= "MIME-Version: 1.0".$passage_ligne;
		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		//==========
		 
		//=====Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_final_txt.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format HTML
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_final_html.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		//==========

		//=====Envoi de l'e-mail.
		mail($mail,$sujet,$message,$header);
		//==========
	}
?>