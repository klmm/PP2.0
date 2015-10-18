<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = ['SHIFFRIN Mikaela',
'HANSDOTTER Frida',
'MAZE Tina',
'STRACHOVA Sarka',
'ZETTEL Kathrin',
'VELEZ ZUZULOVA Veronika',
'PIETILAE-HOLMNER Maria',
'HOLDENER Wendy',
'HOSP Nicole',
'LOESETH Nina',
'GAGNON Marie-Michele',
'THALMANN Carmen',
'NOENS Nastasia',
'MIELZYNSKI Erin',
'COSTAZZA Chiara',
'KIRCHGASSER Michaela',
'SCHILD Bernadette',
'GISIN Michelle',
'MOUGEL Laurie',
'SWENN-LARSSON Anna',
'DUERR Lena',
'STIEGLER Resi',
'SAEFVENBERG Charlotta',
'WIKSTROEM Emelie',
'MOELGG Manuela',
'CURTONI Irene',
'BAUD MUGNIER Adeline',
'DAUM Alexandra',
'HECTOR Sara',
'WIRTH Barbara',
'FEIERABEND Denise',
'EKLUND Nathalie',
'BARTHET Anne-Sophie',
'VLHOVA Petra',
'GEIGER Christina',
'CHABLE Charlotte',
'WIESLER Maren',
'AGER Christina',
'BRIGNONE Federica',
'GRUENWALD Julia',
'BREM Eva-Maria',
'PARDELLER Sarah',
'BARIOZ Taina',
'BUCIK Ana',
'GUTIERREZ Mireia',
'DUBOVSKA Martina',
'SCHMOTZ Marlene',
'VOGEL Nadja',
'CRAWFORD Candace',
'HASEGAWA Emi',
'KOPP Rahel'
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