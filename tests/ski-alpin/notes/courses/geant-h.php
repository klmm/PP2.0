<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['LIGETY Ted',
'FANARA Thomas',
'HIRSCHER Marcel',
'NANI Roberto',
'PINTURAULT Alexis',
'LEITINGER Roland',
'KRISTOFFERSEN Henrik',
'NEUREUTHER Felix',
'FAIVRE Mathieu',
'MUFFAT-JEANDET Victor',
'EISATH Florian',
'ZUBCIC Filip',
'BORSOTTI Giovanni',
'SCHOERGHOFER Philipp',
'MURISIER Justin',
'REICHELT Hannes',
'JITLOFF Tim',
'LUITZ Stefan',
'BALLERIN Andrea',
'MYHRER Andre',
'DOPFER Fritz',
'HIRSCHBUEHL Christian',
'MOELGG Manfred',
'SVINDAL Aksel Lund',
'NOESIG Christoph',
'JANSRUD Kjetil',
'MISSILLIER Steve',
'HAUGEN Leif Kristian',
'ZAMPA Adam',
'ZURBRIGGEN Elia',
'TUKHTAEV Kamiljon',
'KOVBASNYUK Ivan',
'SAMSAL Dalibor',
'DANILOCHKIN Yuri',
'BENIAIDZE Alex',
'DEL CAMPO Juan',
'ULLRICH Max',
'FAVROT Thibaut',
'MAURBERGER Simon',
'MATHIS Marcel',
'GALEOTTI Greg',
'TONETTI Riccardo',
'KRIECHMAYR Vincent',
'NARITA Hideyuki',
'PHILP Trevor',
'KRANJEC Zan',
'ROBERTS Hig',
'KRYZL Krystof',
'NETELAND Bjoernar',
'CHRISTIANSON Kieffer',
'FELLER Manuel',
'ANDRIENKO Aleksander',
'WERRY Tyler',
'WINDINGSTAD Rasmus',
'MEILLARD Loic',
'BAUMANN Romed',
'FORD Tommy',
'BLARDONE Massimiliano',
'PLEISCH Manuel',
'TORSTI Samu',
'LAIKERT Igor',
'POPOV Albert',
'SIMARI BIRKNER Cristian Javier',
'KOTZMANN Adam',
'PIRINEN Eemeli',
'KOSI Klemen',
'CASSMAN Anton',
'CASSE Mattia',
'GENOUD Amaury',
'ISHII Tomoya',
'ROENNGREN Mattias',
'COHEE Nick',
'MALMSTROM Victor',
'TUMLER Thomas',
'SCHMID Alexander',
'DE ALIPRANDINI Luca',
'CAVIEZEL Gino',
'SANDELL Marcus'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE (date_blessure IS NULL OR date_blessure < NOW()) AND retraite=0 AND genre='H' AND note_geant >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_geant . '<br/>';
}
    
?>