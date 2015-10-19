<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['JANKA Carlo',
'PINTURAULT Alexis',
'MUFFAT-JEANDET Victor',
'KOSTELIC Ivica',
'BANK Ondrej',
'HIRSCHER Marcel',
'ZRNCIC DIM Natko',
'CAVIEZEL Mauro',
'ZAMPA Adam',
'MAYER Matthias',
'LIGETY Ted',
'KRIECHMAYR Vincent',
'BAUMANN Romed',
'VILETTA Sandro',
'ZURBRIGGEN Silvan',
'GOLDBERG Jared',
'MERMILLOD BLONDIN Thomas',
'JANSRUD Kjetil',
'BYDLINSKI Maciej',
'KOSI Klemen',
'MURISIER Justin',
'THEAUX Adrien',
'FEUZ Beat',
'FRANZ Max',
'MUZATON Maxence',
'WEIBRECHT Andrew',
'INNERHOFER Christof',
'CASSE Mattia',
'MARSAGLIA Matteo',
'CATER Martin',
'OLSSON Hans',
'KLOTZ Siegmar',
'SCHWEIGER Patrick',
'VRABLIK Martin',
'FERSTL Josef',
'JITLOFF Tim',
'PARIS Dominik',
'PUCHNER Joachim',
'WERRY Tyler',
'KRYZL Krystof',
'TRIKHICHEV Pavel',
'THOMPSON Broderick'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

$sql3 = "UPDATE ski_alpin_athlete SET note_combine=70 WHERE id=?";
$prep3 = $db->prepare($sql3);
foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_combine . '<br/>';
	
	    if($ath->note_combine < 70){
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='H' AND note_combine >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_combine . '<br/>';
}

    
?>