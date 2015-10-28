<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['BRIGNONE Federica',
'SHIFFRIN Mikaela',
'WEIRATHER Tina',
'GUT Lara',
'WORLEY Tessa',
'REBENSBURG Viktoria',
'HECTOR Sara',
'BREM Eva-Maria',
'CURTONI Irene',
'HANSDOTTER Frida',
'FANCHINI Nadia',
'MOELGG Manuela',
'BARIOZ Taina',
'CURTONI Elena',
'PREFONTAINE Marie-Pier',
'GOGGIA Sofia',
'BARTHET Anne-Sophie',
'LOESETH Nina',
'KIRCHGASSER Michaela',
'SUTER Jasmina',
'KLING Kajsa',
'PIETILAE-HOLMNER Maria',
'SIEBENHOFER Ramona',
'HASEGAWA Emi',
'GAGNON Marie-Michele',
'HOLDENER Wendy',
'LAVTAR Katarina',
'MARSAGLIA Francesca',
'SHKANOVA Maria',
'KLICNAROVA Pavla',
'FERK Marusa',
'JELINKOVA Adriana',
'CHABLE Charlotte',
'SCHLEPER Sarah',
'WEINBUCHNER Susanne',
'PROSTEVA Elena',
'MASSIOS Marie',
'EKLUND Nathalie',
'ANDO Asa',
'KAPPAURER Elisabeth',
'MCJAMES Megan',
'FJAELLSTROEM Magdalena',
'HOESL Simona',
'SEJERSTED Lotte Smiseth',
'FRASSE SOMBET Coralie',
'MIRADOLI Romane',
'BRUNNER Stephanie',
'DUERR Lena',
'PAULATHOVA Katerina',
'CRAWFORD Candace',
'GISIN Michelle',
'TILLEY Alexandra',
'AGNELLI Nicole',
'MOWINCKEL Ragnhild',
'KIRKOVA Maria',
'VLHOVA Petra',
'GASIENICA-DANIEL Maryna',
'WIKSTROEM Emelie',
'ALPHAND Estelle',
'GALLHUBER Katharina',
'ROBNIK Tina',
'TRUPPE Katharina',
'DIREZ Clara',
'RESCH Stephanie',
'TOMMY Mikaela',
'STAALNACKE Ylva',
'HAASER Ricarda',
'HUETTER Cornelia',
'PICHLER Karoline',
'DREV Ana',
'BASSINO Marta'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='F' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_geant=60 WHERE id=?";
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
	
	    if($ath->note_geant < 60){
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE (date_blessure IS NULL OR date_blessure < NOW()) AND retraite=0 AND genre='F' AND note_geant >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_geant . '<br/>';
}
    
?>