<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
	
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur == ""){
        auto_login();
        $loginjoueur = $_SESSION['LoginJoueur'];
    }

    if($loginjoueur == ""){
	$msg = 'Vous n\'êtes pas connecté...';
	$success_avatar = false;
	
        echo json_encode(array('msg' => $msg, 'success' => $success_avatar));
	return;
    }
    //------------------------------------------------------------------------------------------------//
    
    
    //--------------------------------------PARAMETRES--------------------------------------//
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $slogan = $_POST['punchline'];
    //------------------------------------------------------------------------------------------------//
    
    $target = '/img/avatars/';
    $max_size = 100000;
    $max_width = 800;
    $max_height = 800;

    $tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
    $infosImg = array();
    
    $final_avatar_path = null;
    $success_avatar = false;
    if($mail != '' && $mail != null){
	if( !empty($_FILES['panelUpload']['name']) ){
	    $extension  = pathinfo($_FILES['panelUpload']['name'], PATHINFO_EXTENSION);

	    if(in_array(strtolower($extension),$tabExt)){
		$infosImg = getimagesize($_FILES['panelUpload']['tmp_name']);

		if($infosImg[2] >= 1 && $infosImg[2] <= 14){
		    if(($infosImg[0] <= $max_width) && ($infosImg[1] <= $max_height) && (filesize($_FILES['panelUpload']['tmp_name']) <= $max_size) && strlen($_FILES['panelUpload']['name']) < 40){
			if(isset($_FILES['panelUpload']['error']) && UPLOAD_ERR_OK === $_FILES['panelUpload']['error']){
			    $nomImage = $_FILES['panelUpload']['name'];

			    if(move_uploaded_file($_FILES['panelUpload']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $target . $loginjoueur . '-' . $nomImage)){
				$success_avatar = true;
				$final_avatar_path = $target . $loginjoueur . '-' . $nomImage;
			    }
			    else{
				$msg = 'Problème lors de l\'upload !';
			    }
			}
			else{
			    $msg = 'Une erreur interne a empêché l\'uplaod de l\'image';
			}
		    }
		    else{
			$msg = 'Erreur dans les dimensions de l\'image (max : 800x800, 100ko) !';
		    }
		}
		else{
		    $msg = 'Le fichier à uploader n\'est pas une image !';
		}
	    }
	    else{
		$msg = 'L\'extension du fichier est incorrecte !';
	    }
	}
	else{
	    $success_avatar = true;
	}
	
	if($success_avatar == true){ 
	    if(update_joueur_infos($loginjoueur,$nom,$prenom,$mail,$final_avatar_path,$slogan)){
		//header("Location: /configuration");
		$msg = 'Informations enregistrées !';
		echo json_encode(array('msg' => $msg, 'success' => true));
		return;
	    }
	    else{
		//header("Location: /configuration");
		$msg = 'Erreur lors de l\'enregistrement de vos informations !';
		echo json_encode(array('msg' => $msg, 'success' => false));
		return;
	    }    
	}
	else{
	    //header("Location: /configuration");
	    echo json_encode(array('msg' => $msg, 'success' => false));
	    return;
	}
    }
    else{
	$msg = 'Veuillez renseigner une adresse mail valide !';
	$success_avatar = false;
    }
    
    //header("Location: /configuration");

    echo json_encode(array('msg' => $msg, 'success' => $success_avatar));
    return;

?>