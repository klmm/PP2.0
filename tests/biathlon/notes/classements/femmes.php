<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_athlete.php');
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $athletes = [
	'DOMRACHEVA Darya',
'MAKARAINEN Kaisa',
'SEMERENKO Valj',
'VITKOVA Veronika',
'HILDEBRAND Franziska',
'SOUKALOVA Gabriela',
'WIERER Dorothea',
'DAHLMEIER Laura',
'PREUSS Franziska',
'OBERHOFER Karin',
'ECKHOFF Tiril',
'BESCOND Anais',
'GLAZYRINA Ekaterina',
'NOWAKOWSKA Weronika',
'DORIN HABERT Marie',
'VIROLAYNEN Daria',
'DUNKLEE Susan',
'SKARDINO Nadezhda',
'GEREKOVA Jana',
'HINZ Vanessa',
'CRAWFORD Rosanna',
'GREGORIN Teja',
'GASPARIN Elisa',
'DZHYMA Juliya',
'PODCHUFAROVA Olga',
'HAUSER Lisa Theresa',
'SHUMILOVA Ekaterina',
'LATUILLIERE Enora',
'HOJNISZ Monika',
'BIRKELAND Fanny Horn',
'YURLOVA Ekaterina',
'BOLLIET Marine',
'PUSKARCIKOVA Eva',
'KUMMER Luise',
'GONTIER Nicole',
'LAUKKANEN Mari',
'TANDY Megan',
'GUZIK Krystyna',
'TACHIZAKI Fuyuko',
'ABRAMOVA Olga',
'PISAREVA  Nadzeya',
'RINGEN Elise',
'BURDYGA Natalya',
'ROMANOVA Yana',
'VARVYNETS Iryna',
'OLSBU Marte',
'GWIZDON Magdalena',
'LANDOVA Jitka',
'TOFALVI Eva',
'DUBAREZAVA Nastassia',
'BRAISAZ Justine',
'INNERHOFER Katharina',
'DREISSIGACKER Hannah',
'MALI Andreja',
'KRYUKO Iryna',
'BOILLEY Sophie',
'VARCIN Coline',
'HOEGBERG Elisabeth',
'ZDOUC Dunja',
'GASPARIN Aita',
'HAECKI Lena',
'BONDAR Iana',
'BRORSSON Mona',
'SANFILIPPO Federica',
'LEHTLA Kadri',
'VITTOZZI Lisa',
'USANOVA Darya',
'TOMESOVA Barbora',
'TANG Jialin',
'USLUGINA Irina',
'GOESSNER Miriam',
'COOK Annelies',
'HORCHLER Karolin',
'FIALKOVA Paulina',
'YORDANOVA Emilia',
'SOLEMDAL Synnoeve',
'POLIAKOVA Terezia',
'VAILLANCOURT Audrey',
'STOYANOVA Desislava',
'TANAKA Yurie',
'ZHURAVOK Yuliya',
'PISCORAN Luminita',
'KISTANOVA  Anna',
'NICOLAISEN Kaia Woeien',
'BIELKINA Nadiia',
'SELEDTSOVA Evgenia',
'BACHMANN Tina',
'KNOLL Annika',
'NECHKASOVA Galina',
'CHRAPANOVA Martina',
'KOCERGINA Natalija',
'IAKUSHOVA Olga',
'NILSSON Emma',
'TALIHAERM Johanna',
'LESCINSKAITE Gabriele',
'EGAN Clare',
'RAIKOVA Alina'
];

echo 'ATHLETES DU CLASSEMENT<br/>';
    
$sql = "SELECT * FROM biathlon_athlete WHERE genre='F' AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
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
	    echo round(($ath->note_couche+$ath->note_debout+$ath->note_fond)/3) . ' / ' . $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . '<br/>';

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

$sql2 = "SELECT * FROM biathlon_athlete WHERE retraite=0 AND genre='F' AND id NOT IN ($ids)";
$prep2 = $db->prepare($sql2);
$prep2->setFetchMode(PDO::FETCH_OBJ);
$prep2->execute();


while($ath = $prep2->fetch()){
    echo $ath->nom . ' ' . $ath->prenom . ' - ' . $ath->note_couche . ' - ' . $ath->note_debout . ' - ' . $ath->note_fond . ' - ' . '<br/>';
}
    
?>