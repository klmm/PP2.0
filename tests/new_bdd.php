<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi_new.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();

    //On fait la requete sur le login
    $sql = "SELECT * FROM test";
    $prep = $db->prepare($sql);
    $prep->execute();
    $prep->setFetchMode(PDO::FETCH_OBJ);

    //On fait le test si un enrengistrement a �t� trouv�
    while( $enregistrement = $prep->fetch() )
    {
	    echo $enregistrement->id_test . '<br/>';
	    echo $enregistrement->texte . '<br/>'  . '<br/>';
    }
    $db = null;

?>