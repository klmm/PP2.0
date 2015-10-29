<?php	
    
    function get_badges_tous(){
	// On �tablit la connexion avec la base de donn�es
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM joueurs_badge";
	$prep = $db->prepare($sql);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	//On fait le test si un enrengistrement a �t� trouv�
	while( $enregistrement = $prep->fetch() )
	{
	    $joueur = $enregistrement->joueur;
	    $sport = $enregistrement->sport;
	    $nom_badge = $enregistrement->nom_badge;
	    $classement = $enregistrement->classement;
	    
	    $class_badge = 'b-';
	    switch($sport){
		case "Cyclisme":
		    $class_badge .= 'cyclisme-';
		    break;
		
		case "Football":
		    $class_badge .= 'football-';
		    break;
		
		case "Ski alpin":
		    $class_badge .= 'ski-';
		    break;
		
		case "Biathlon":
		    $class_badge .= 'biathlon-';
		    break;
		
		case "Rugby":
		    $class_badge .= 'rugby-';
		    break;
	    }
	    
	    switch($classement){
		case 1:
		    $class_badge .= 'gold';
		    $classement = '1er';
		    break;
		
		case 2:
		    $class_badge .= 'silver';
		    $classement = '2ème';
		    break;
		
		case 3:
		    $class_badge .= 'bronze';
		    $classement = '3ème';
		    break;
	    }
   
	    $arr[$joueur][] = array(
		'sport' => $sport,
		'nom_badge' => $nom_badge,
		'classement' => $classement,
		'class_badge' => $class_badge
	    );
	}
	$db = null;
	return $arr;
    }
    
?>