<?php

/*require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $sql = "UPDATE cyclisme_athlete SET note_baroudeur=(note_paves+note_vallons)/2 WHERE id_cyclisme_athlete=1";

    for ($i = 0 ; $i < 150 ; $i++){
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_article,PDO::PARAM_INT);
	$prep->bindValue(2,$login,PDO::PARAM_STR);
	$prep->bindValue(3,$contenu,PDO::PARAM_STR);
	$res_req = $prep->execute();
    }*/

    //echo $login . ' - ' . $contenu . ' - ' . $id_article . ' - ';
    
		


   /* require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $icone = '/img/drapeaux/32x21/';
    $petit = '/img/drapeaux/48x32/';
    $moyen = '/img/drapeaux/64x43/';
    $grand = '/img/drapeaux/128x85/';

    $sql = "UPDATE Pays SET drapeau_icone=' . $icone . ', drapeau_petit=?, drapeau_moyen=?, drapeau_grand=?";
    $prep = $db->prepare($sql);
    $prep->setFetchMode(PDO::FETCH_OBJ);
    $prep->bindValue(1,$icone,PDO::PARAM_STR);
    $prep->bindValue(2,$petit,PDO::PARAM_STR);
    $prep->bindValue(3,$moyen,PDO::PARAM_STR);
    $prep->bindValue(4,$grand,PDO::PARAM_STR);

    $prep->execute();*/
     
       /* $characters = 'ACDEFGHJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        
        $j = 0;
        for($i=0;$i<10;$i++){
            $newpass = '';
            for ($j = 0; $j < 5; $j++) {
                $newpass .= $characters[rand(0, $charactersLength - 1)];
            }

            $nb_al = rand(0,4);

            $arr[$i]['nombre'] = $nb_al;
            $arr[$i]['chaine'] = $newpass;
        }
        
        echo 'Tableau :<br/>';
        print_r($arr);
      
        $sort = array();
        foreach($arr as $k => $v) {
            $sort['nombre'][$k] = $v['nombre'];
            $sort['chaine'][$k] = $v['chaine'];
        }
 
        array_multisort($sort['nombre'], SORT_ASC, $arr);
        
        echo '<br/><br/>Tableau trié nombre :<br/>';
        print_r($arr);
        
        array_multisort($sort['chaine'], SORT_ASC, $arr);
        
        echo '<br/><br/>Tableau trié chaine :<br/>';
        print_r($arr);
        
        array_multisort($sort['nombre'], SORT_DESC, $sort['chaine'], SORT_ASC, $arr);
        
        echo '<br/><br/>Tableau trié nombre puis chaine :<br/>';
        print_r($arr);
        
        array_multisort($sort['chaine'], SORT_DESC, $sort['nombre'], SORT_ASC, $arr);
        
        echo '<br/><br/>Tableau trié chaine puis nombre :<br/>';
        print_r($arr);*/

	/*echo '1<br/>';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/inscriptions/update_inscriptions.php');
	echo '2<br/>';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/get_joueurs.php');
	echo '3<br/>';
	add_inscription(4,'Toto');
	add_inscription(4,'Kevin');
	add_inscription(4,'Tututu');
	echo '4<br/>';
	$arr = get_joueurs_inscrits(4);
	echo '5<br/>';
	echo 'Joueurs inscrits :<br/>';
	print_r($arr);*/

	
	
	//echo 'TXT : <br/>' . $contenu_txt;
	
	/*require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On prépare la requête pour aller chercher les articles
	$sql = "SELECT * FROM cyclisme_athlete";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->execute();
	
	//On met les articles dans le tableau
	$sql2 = "SELECT * FROM cyclisme_equipe WHERE nom_complet = ?";
	$prep2 = $db->prepare($sql2);
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
	$sql3 = "UPDATE cyclisme_athlete SET id_equipe = ? WHERE id_cyclisme_athlete = ?";
	$prep3 = $db->prepare($sql3);
	$prep3->setFetchMode(PDO::FETCH_OBJ);
			
	while( $enregistrement = $prep->fetch() )
	{
		$id_cycliste = $enregistrement->id_cyclisme_athlete;
		$equipe = $enregistrement->Equipe;
		
		echo $id_cycliste . ' - ' . $equipe . '<br/>';
		
		$prep2->bindValue(1,$equipe,PDO::PARAM_STR);
		$prep2->execute();
		
		$res = $enregistrement2 = $prep2->fetch();
		
		if ( $res ){
			$id_equipe = $enregistrement2->id_cyclisme_equipe;
			$prep3->bindValue(1,$id_equipe,PDO::PARAM_INT);
			$prep3->bindValue(2,$id_cycliste,PDO::PARAM_INT);
			$prep3->execute();
		}
	}*/
        
       // echo '</html>';
?>