<?php

$loginjoueur = $_SESSION['LoginJoueur'];
$idjoueur = $_SESSION['IDJoueur'];

if ($loginjoueur == ""){
echo "
	<div class='section-contact section' id='inscription' style='min-height: 214px; background: rgb(241, 241, 241);'>
		<div id='inscription-container' class='container marketing '>
			
			<div class='sectionSide'>
				<h2 class='section-heading'>Inscription</h2>
				<p class='section-highlight'>Quelques infos avant les pronos !</p>
			</div>
			<form id='inscription-form' role='form' class='row contact-form' action='sql/joueurs/add_joueur.php' method='POST'>
				<div class='col-md-6'>
						<input type='text' placeholder='Nom' name='nom' class='form-control'>
						<input type='text' placeholder='Prénom' name='prenom' class='form-control'>
						<input type='text' placeholder='Login (entre 3 et 12 chiffres et lettres)' name='login' class='form-control' required='' data-validation-required-message='Veuillez choisir un login'>
				</div>
				<div class='col-md-6'>
						<input type='email' placeholder='email' name='email' class='form-control' required='' data-validation-required-message='Veuillez indiquer votre adresse mail'>
						<input type='password' placeholder='Mot de passe (entre et 8 et 20 chiffres et lettres)' name='motdepasse' class='form-control' required='' data-validation-required-message='Veuillez choisir un mot de passe'>
						<input type='password' placeholder='Confirmation du mot de passe' name='confmotdepasse' class='form-control' required='' data-validation-required-message='Veuillez confirmer votre mot de passe'>
						
				</div>
				<div class='col-md-4'></div>
				<div class='col-md-4'>
					<button type='submit' class='btn btn-block btn-primary' style='padding:0;margin-top:20px;margin-bottom:10px;text-align:center;'>
						<span style='display:block;padding: 0 8px 0 8px;height:38px;line-height:38px;'>Envoyer</span>
					</button>
					
				</div>
				<div class='col-md-4'></div>
			</form>
		</div>
	</div>";
}

?>