<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = [
	'FOURCADE Martin',
'SHIPULIN Anton',
'FAK Jakov',
'SCHEMPP Simon',
'BOE Johannes Thingnes',
'MORAVEC Ondrej',
'GARANICHEV Evgeniy',
'SLESINGR Michal',
'SVENDSEN Emil Hegle',
'LESSER Erik',
'FOURCADE Simon',
'LINDSTROEM Fredrik',
'PEIFFER Arnd',
'BJOERNDALEN Ole Einar',
'EDER Simon',
'SMITH Nathan',
'WEGER Benjamin',
'BOEHM Daniel',
'BOE Tarjei',
'BEATRIX Jean Guillaume',
'DOLL Benedikt',
'LANDERTINGER Dominik',
'FILLON MAILLET  Quentin',
'BIRNBACHER Andreas',
'ILIEV Vladimir',
'SEMENOV Sergey',
'RASTORGUJEVS Andrejs',
'ANEV Krasimir',
'LAPSHIN Timofey',
'BAILEY Lowell',
'MALYSHKO Dmitry',
'GREEN Brendan',
'TSVETKOV Maxim',
'BURKE Tim',
'OS Alexander',
'LIADOV Yuryi',
'MESOTITSCH Daniel',
'HOFER Lukas',
'WINDISCH Dominik',
'NORDGREN Leif',
'SOUKUP Jaroslav',
'CHEPELIN Vladimir',
'PIDRUCHNYI Dmytro',
'BAUER Klemen',
'BJOENTEGAARD Erlend',
'DOLDER Mario',
'EBERHARD Julian',
'LESSING Roland',
'ABEE-LUND Henrik',
'ROESCH Michael',
'KRCMAR Michal',
'DE LORENZI Christian',
'PRYMA Artem',
'KUEHN Johannes',
'KAZAR Matej',
'DESTHIEUX Simon',
'GROSSEGGER Sven',
'VOLKOV Alexey',
'BIRKELAND Lars Helge',
'TYSHCHENKO Artem',
'PECHENKIN Aleksandr',
'GUIGONNAT Antonin',
'PUCHIANU Cornel',
'SAVITSKIY Yan',
'GRAF Florian',
'KRUPCIK Tomas',
'BORMOLINI Thomas',
'CHRISTIANSEN Vetle Sjastad',
'ELISEEV Matvey',
'SLEPOV Alexey',
'ERMITS Kalev',
'WIESTNER Serafin',
'JOLLER Ivan',
'KOMATZ David',
'OTCENAS Martin',
'FEMLING Peppe',
'ARWIDSON Tobias',
'ZHYRNYI Oleksander',
'GOW Scott',
'KOIV Kauri',
'FINELLO Jeremy',
'ERIKSSON Christofer',
'EBERHARD Tobias',
'ARMGREN Ted',
'DYUZHEV Dmitriy',
'MARIC Janez',
'KOBONOKI Tsukasa',
'TOIVANEN Ahti',
'MATIASKO Miroslav',
'JACKSON Lee-Steve',
'HASILLA Tomas'
];

echo 'ATHLETES DU CLASSEMENT<br/>';
    
$sql = "SELECT * FROM biathlon_athlete WHERE genre='H' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
$prep = $db->prepare($sql);
$prep->setFetchMode(PDO::FETCH_OBJ);

foreach ($athletes as $athlete){
    $nom = explode(' ', $athlete);
    $prep->bindValue(1,'%' . $nom[0] . '%',PDO::PARAM_STR);
    $prep->bindValue(2,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
    $prep->execute();
    $ath = $prep->fetch();
    
    if($ath){
	if($ath->retraite == 0){
	    echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . '<br/>';

	    $arr_id[] = $ath->id;
	}
    }
    else{
	echo '>>>>>>' . $nom[0] . ' ' . $nom[sizeof($nom) - 1] . '<br/>';
    }
}

echo '<br/>';
echo 'ATHLETES NON RECENSES DANS CE CLASSEMENT<br/>';

$ids = implode(',',$arr_id);

$sql2 = "SELECT * FROM biathlon_athlete WHERE retraite=0 AND genre='H' AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_couche . ' - ' . $ath->note_debout . ' - ' . $ath->note_fond . ' - ' . '<br/>';
}
    
?>