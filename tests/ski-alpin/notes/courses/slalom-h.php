<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['HIRSCHER Marcel',
'NEUREUTHER Felix',
'KHOROSHILOV Alexander',
'KRISTOFFERSEN Henrik',
'DOPFER Fritz',
'GROSS Stefano',
'HARGIN Mattias',
'RAZZOLI Giuliano',
'FOSS-SOLEVAAG Sebastian',
'PINTURAULT Alexis',
'LARSSON Markus',
'MYHRER Andre',
'GRANGE Jean-Baptiste',
'MUFFAT-JEANDET Victor',
'LIZEROUX Julien',
'YULE Daniel',
'BAECK Axel',
'THALER Patrick',
'BYGGMARK Jens',
'LINDH Calle',
'STRASSER Linus',
'HERBST Reinfried',
'RAICH Benjamin',
'LAHDENPERAE Anton',
'CHODOUNSKY David',
'MOELGG Manfred',
'AERNI Luca',
'YUASA Naoki',
'MATT Mario',
'RYDING Dave',
'KOSTELIC Ivica',
'ZAMPA Adam',
'NORDBOTTEN Jonathan',
'DEVILLE Cristian',
'LYSDAHL Espen',
'ZENHAEUSERN Ramon',
'MISSILLIER Steve',
'HOERL Wolfgang',
'LIGETY Ted',
'SCHMID Philipp',
'ZUBCIC Filip',
'MATT Michael',
'COUSINEAU Julien',
'TONETTI Riccardo',
'HAUGEN Leif Kristian',
'BRANDENBURG Will',
'TRIKHICHEV Pavel',
'NIEDERBERGER Bernhard',
'STEHLE Dominik',
'MURISIER Justin',
'SCHMIDIGER Reto',
'READ Erik',
'KRYZL Krystof',
'SKUBE Matic',
'KUERNER Miha'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_slalom=70 WHERE id=?";
$prep3 = $db->prepare($sql3);
foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_slalom . '<br/>';
	
	    if($ath->note_slalom < 70){
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='H' AND note_slalom >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_slalom . '<br/>';
}

    
?>