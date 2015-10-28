<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['VONN Lindsey',
	    'FENNINGER Anna',
	    'MAZE Tina',
	    'GOERGL Elisabeth',
	    'FANCHINI Elena',
	    'GUT Lara',
	    'WEIRATHER Tina',
	    'REBENSBURG Viktoria',
	    'SUTER Fabienne',
	    'YURKIW Larisa',
	    'ROSS Laurenne',
	    'HOSP Nicole',
	    'MANCUSO Julia',
	    'COOK Stacey',
	    'KLING Kajsa',
	    'GISIN Dominique',
	    'RUIZ CASTILLO Carolina',
	    'HUETTER Cornelia',
	    'MERIGHETTI Daniela',
	    'SCHMIDHOFER Nicole',
	    'MIKLOS Edit',
	    'STUHEC Ilka',
	    'MCKENNIS Alice',
	    'SCHNARF Johanna',
	    'FANCHINI Nadia',
	    'PUCHNER Mirjam',
	    'STUFFER Verena',
	    'JNGLIN-KAMER Nadja',
	    'ABDERHALDEN Marianne',
	    'FISCHBACHER Andrea',
	    'MOWINCKEL Ragnhild',
	    'SEJERSTED Lotte Smiseth',
	    'BAILET Margot',
	    'STERZ Regina',
	    'SIEBENHOFER Ramona',
	    'TVIBERG Maria Therese',
	    'PIOT Jennifer',
	    'WILES Jacqueline',
	    'MOSER Stefanie',
	    'VENIER Stephanie',
	    'WENIG Michaela',
	    'MARSAGLIA Francesca',
	    'COLETTI Alexandra',
	    'NUFER Priska',
	    'PELLISSIER Marion',
	    'SUTER Corinne'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='F' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_descente=70 WHERE id=?";
$prep3 = $db->prepare($sql3);
foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_descente . '<br/>';
	
	    if($ath->note_descente < 70){
		$prep3->bindValue(1,$ath->id);
		$prep3->execute();
	    }

	    $arr_id[] = $ath->id;
	}
    }
    else{
	echo '>>>>>>' . $nom[0] . ' ' . $nom[sizeof($nom) - 1] . '<br/>';
    }
}

echo '<br/>';
echo 'ATHLETES A PLUS DE 65 DANS LA BASE, NON RECENSES DANS CE CLASSEMENT<br/>';

$ids = implode(',',$arr_id);

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='F' AND note_descente >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_descente . '<br/>';
}

    
?>