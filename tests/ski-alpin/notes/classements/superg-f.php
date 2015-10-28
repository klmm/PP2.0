<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['VONN Lindsey',
'FENNINGER Anna',
'MAZE Tina',
'HUETTER Cornelia',
'GUT Lara',
'HOSP Nicole',
'GOERGL Elisabeth',
'WEIRATHER Tina',
'FANCHINI Nadia',
'MARSAGLIA Francesca',
'MANCUSO Julia',
'SCHMIDHOFER Nicole',
'REBENSBURG Viktoria',
'GISIN Dominique',
'KLING Kajsa',
'CURTONI Elena',
'BRIGNONE Federica',
'ROSS Laurenne',
'FANCHINI Elena',
'SUTER Fabienne',
'STUFFER Verena',
'STERZ Regina',
'MERIGHETTI Daniela',
'MOWINCKEL Ragnhild',
'JAY MARCHAND-ARVIER Marie',
'PUCHNER Mirjam',
'BAILET Margot',
'STUHEC Ilka',
'COOK Stacey',
'RUIZ CASTILLO Carolina',
'GAGNON Marie-Michele',
'NUFER Priska',
'PIOT Jennifer',
'WORLEY Tessa',
'GRENIER Valerie',
'HRONEK Veronique',
'SCHNARF Johanna',
'MIRADOLI Romane',
'TIPPLER Tamara',
'FISCHBACHER Andrea',
'YURKIW Larisa',
'SIEBENHOFER Ramona',
'KRIZOVA Klara',
'TVIBERG Maria Therese',
'MIKLOS Edit',
'VENIER Stephanie',
'BASSINO Marta',
'ROLLAND Marion',
'PELLISSIER Marion',
'MCKENNIS Alice',
'WENIG Michaela',
'HOLDENER Wendy',
'SEJERSTED Lotte Smiseth',
'SUTER Corinne',
'FERK Marusa',
'HOLTMANN Mina Fuerst',
'BRODNIK Vanja',
'GOGGIA Sofia',
'WILES Jacqueline'
];

echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';
    
$sql = "SELECT * FROM ski_alpin_athlete WHERE genre='F' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
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

$sql2 = "SELECT * FROM ski_alpin_athlete WHERE retraite=0 AND genre='F' AND note_superg >= 65 AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_superg . '<br/>';
}

    
?>