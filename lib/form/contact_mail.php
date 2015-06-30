<?php
	$nom = $_POST["nom"];
	$mail = $_POST["mail"];
	$contenu = $_POST["contenu"];
	$sujet = $_POST["objet"];

	$contenu_html = nl2br(htmlentities($contenu));
	
	$passage_ligne = "\r\n";
	
	//=====Cr�ation de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	
	//=====Cr�ation du header de l'e-mail.
	$header = 'From: "Parions Potes"<parionsp@20gp.ovh.net>'.$passage_ligne;
	$header.= 'Reply-to: "'. $nom . '" <' . $mail . '>'.$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Cr�ation du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	
	
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	
	
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu_html.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	//==========

	//=====Envoi de l'e-mail.
	mail('contact@parions-potes.fr',$sujet,$message,$header);
	//==========
	
	echo('success;Mail envoyé. Nous vous répondons dans les plus brefs délais.');
?>