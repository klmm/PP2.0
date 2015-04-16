<?php

	function envoi_mails($joueurs, $sujet, $contenu)
	{
		foreach ($joueurs as $joueur) {
			$login_joueur = $joueur[0];
			$mail_joueur = $joueur[1];
			
			envoi_mail($login_joueur, $mail_joueur, $sujet, $contenu);
		}
	}
	
	function envoi_mail($login, $mail, $objet, $contenu_txt){
	
		$contenu_html = nl2br($contenu_txt);
	
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
		
		// Création de la boundary
		$boundary = "-----=".md5(rand());
		
		// Texte
		$entete_txt = "Salut " . $login . ",";
		$signature_txt = "Sportivement," . $passage_ligne . $passage_ligne . "L'équipe Parions Potes" . $passage_ligne . "www.parions-potes.fr";
			
		// Html
		$entete_html = "<html><p style= 'font: serif'>Salut " . $login . ",<br/><br/>";
		$signature_html = "<br/><br/>Sportivement,<br/><br/>L'équipe Parions Potes<br/>www.parions-potes.fr</p></html>";
		
		// Ecriture du message avec en-tête, signature
		$message_final_txt = $entete_txt . $passage_ligne . $passage_ligne . $contenu_txt . $passage_ligne . $passage_ligne . $signature_txt;
		$message_final_html = $entete_html . $contenu_html . $signature_html;
	
		// Création du header de l'e-mail.
		$header = "From: \"Parions Potes\"<parionsp@20gp.ovh.net>".$passage_ligne;
		$header.= "Reply-to: \"Parions Potes\" <contact@parions-potes.fr>".$passage_ligne;
		$header.= "MIME-Version: 1.0".$passage_ligne;
		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		 
		// Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		
		// Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_final_txt.$passage_ligne;
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		
		// Ajout du message au format HTML
		$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_final_html.$passage_ligne;

		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		$message.= $passage_ligne."--".$boundary."--".$passage_ligne;

		// Envoi de l'e-mail.
		mail($mail,$objet,$message,$header);
	}
?>