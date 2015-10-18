<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['FENNINGER Anna',
'BREM Eva-Maria',
'SHIFFRIN Mikaela',
'HECTOR Sara',
'MAZE Tina',
'FANCHINI Nadia',
'BRIGNONE Federica',
'ZETTEL Kathrin',
'REBENSBURG Viktoria',
'WEIRATHER Tina',
'MOWINCKEL Ragnhild',
'CURTONI Irene',
'WORLEY Tessa',
'HANSDOTTER Frida',
'PREFONTAINE Marie-Pier',
'PIETILAE-HOLMNER Maria',
'MOELGG Manuela',
'LINDELL-VIKARBY Jessica',
'GISIN Dominique',
'BASSINO Marta',
'LOESETH Nina',
'KIRCHGASSER Michaela',
'DREV Ana',
'GUT Lara',
'GOERGL Elisabeth',
'MARMOTTAN Anemone',
'BAUD MUGNIER Adeline',
'BARIOZ Taina',
'VONN Lindsey',
'MARSAGLIA Francesca',
'FISCHBACHER Andrea',
'AGNELLI Nicole',
'CURTONI Elena',
'GAGNON Marie-Michele',
'BERTRAND Marion',
'LAVTAR Katarina',
'KLING Kajsa',
'GISIN Michelle',
'MANCUSO Julia',
'SIEBENHOFER Ramona',
'HASEGAWA Emi',
'PICHLER Karoline',
'BARTHET Anne-Sophie',
'EKLUND Nathalie',
'FRASSE SOMBET Coralie',
'HOLDENER Wendy',
'HRONEK Veronique',
'PAULATHOVA Katerina',
'MCJAMES Megan',
'HOESL Simona',
'THALMANN Carmen'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='F' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_geant=70 WHERE id=?";
$prep3 = $db->prepare($sql3);
foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_geant . '<br/>';
	
	    if($ath->note_geant < 70){
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='F' AND note_geant >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_geant . '<br/>';
}
    
?>