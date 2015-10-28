<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_joueurs.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/mail/envoi_mails.php';

    $id_jeu = $_POST["id_jeu"];
    $id_cal = $_POST["id_cal"];
    $id_discipline = $_POST["id_discipline"];
    $objet = $_POST["objet"];
    $contenu = $_POST["contenu"];
    $dest = $_POST["dest"];
    $envoi = $_POST["envoi"];

// --------------- DESTINATAIRES

    switch($dest){
	case "tous":			
	    $joueurs = get_joueurs_tous();
	break;

	case "admins":
	    $joueurs = get_joueurs_admins();
	break;

	case "jeu":
	    $joueurs = get_joueurs_inscrits($id_jeu,1);
	break;

	case "oubli":
	    $joueurs = get_joueurs_oubli_paris($id_jeu, $id_cal, $id_discipline);
	break;

	case "oubli_premier":
	    $joueurs = get_joueurs_oubli_premier_pari($id_jeu, $id_cal);
	break;
    }
	
// -----------------------------------
	
    echo '
    <html>
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>

	<body>';
	
	if($envoi){
	    echo '
	    <h2>' . sizeof($joueurs) . ' mails envoyés</h2>';
	    envoi_mails($joueurs, $objet, $contenu);
	}
	else{
	    echo '
	    <h2>' . sizeof($joueurs) . ' mails NON envoyés</h2>';
	}

	
	foreach ($joueurs as $key => $joueur){
	    if($joueur['no_mail'] == false){
		echo $joueur['login'] . ' - ' . $joueur['mail'] . '<br/>';
	    }
	}
	
	echo '
	</body>
    </html>';
    
?>