<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['JANSRUD Kjetil',
'PARIS Dominik',
'MAYER Matthias',
'REICHELT Hannes',
'COOK Dustin',
'KRIECHMAYR Vincent',
'THEAUX Adrien',
'FRANZ Max',
'DEFAGO Didier',
'PINTURAULT Alexis',
'JANKA Carlo',
'WEIBRECHT Andrew',
'BAUMANN Romed',
'STRIEDINGER Otmar',
'ROGER Brice',
'STREITBERGER Georg',
'MARSAGLIA Matteo',
'KUENG Patrick',
'CAVIEZEL Mauro',
'FILL Peter',
'INNERHOFER Christof',
'FEUZ Beat',
'CLAREY Johan',
'HIRSCHER Marcel',
'OSBORNE-PARADIS Manuel',
'KILDE Aleksander Aamodt',
'GANONG Travis',
'PUCHNER Joachim',
'VILETTA Sandro',
'HUDEC Jan',
'MERMILLOD BLONDIN Thomas',
'TUMLER Thomas',
'HEEL Werner',
'PRIDY Morgan',
'FAYED Guillermo',
'SCHWEIGER Patrick',
'CASSE Mattia',
'BANK Ondrej',
'LIGETY Ted',
'NYMAN Steven',
'GOLDBERG Jared',
'BRANDNER Klaus',
'SANDER Andreas',
'KLINE Bostjan',
'FERSTL Josef',
'KOSI Klemen',
'SCHMED Fernando',
'JITLOFF Tim',
'BIESEMEYER Thomas',
'WEBER Ralph',
'BERTHOD Marc',
'ZRNCIC DIM Natko',
'SULLIVAN Marco',
'GLEBOV Alexander',
'DUERAGER Markus',
'NETELAND Bjoernar',
'SCHEIBER Florian',
'OLSSON Hans',
'KLOTZ Siegmar',
'MANI Nils'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_superg=70 WHERE id=?";
$prep3 = $db->prepare($sql3);
foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_superg . '<br/>';
	
	    if($ath->note_superg < 70){
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='H' AND note_superg >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_superg . '<br/>';
}
    
?>