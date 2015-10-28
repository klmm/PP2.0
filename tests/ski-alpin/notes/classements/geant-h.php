<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['HIRSCHER Marcel',
'PINTURAULT Alexis',
'LIGETY Ted',
'DOPFER Fritz',
'FANARA Thomas',
'KRISTOFFERSEN Henrik',
'MUFFAT-JEANDET Victor',
'NEUREUTHER Felix',
'RAICH Benjamin',
'NANI Roberto',
'HAUGEN Leif Kristian',
'JANKA Carlo',
'CAVIEZEL Gino',
'EISATH Florian',
'SIMONCELLI Davide',
'FAIVRE Mathieu',
'JITLOFF Tim',
'SANDELL Marcus',
'JANSRUD Kjetil',
'LUITZ Stefan',
'SCHOERGHOFER Philipp',
'OLSSON Matts',
'BORSOTTI Giovanni',
'NOESIG Christoph',
'ZUBCIC Filip',
'TORSTI Samu',
'MYHRER Andre',
'PLEISCH Manuel',
'BLARDONE Massimiliano',
'COOK Dustin',
'MURISIER Justin',
'RICHARD Cyprien',
'ZURBRIGGEN Elia',
'LINDH Calle',
'BANK Ondrej',
'FORD Tommy',
'KRANJEC Zan',
'BROWN Phil',
'CHODOUNSKY David',
'MOELGG Manfred',
'PHILP Trevor',
'LEITINGER Roland',
'BALLERIN Andrea',
'MISSILLIER Steve',
'KRYZL Krystof',
'MAYER Matthias',
'REICHELT Hannes',
'KRIECHMAYR Vincent',
'TUMLER Thomas',
'PIRINEN Eemeli'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='H' AND note_geant >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_geant . '<br/>';
}
    
?>