<?php

$loginjoueur = $_SESSION['LoginJoueur'];
$idjoueur = $_SESSION['IDJoueur'];
$mailjoueur = $_SESSION['MailJoueur'];

echo "<div class='section-contact section' id='contacts' style='min-height: 214px; background: rgb(241, 241, 241);'>
		<div class='container marketing '>
			<div id='contact-container' class='row'>
				<div class='sectionSide'>
					<h2 class='section-heading'>Contact</h2>
					<p class='section-highlight'>Un souci, une question, une idée? N'hésitez pas à nous contacter !</p>
				</div>
		
				<form id='contact-form' role='form' class='row contact-form' action='mail/contact_mail.php' method='POST'>
					<div class='col-md-6'>";
					if ($loginjoueur == ""){
						echo "<input type='text' placeholder='Nom' name='nom' class='form-control' required='' data-validation-required-message='Nom obligatoire'>
							<input type='email' placeholder='email (pour vous répondre)' name='mail' class='form-control' required='' data-validation-required-message='Mail obligatoire'>";
					}
					else{
						echo "<input type='text'  value='" . $loginjoueur . "' name='nom' class='form-control' readonly>
						<input type='email'  value='" . $mailjoueur . "' name='mail' class='form-control' readonly>";
					}
							
							
echo "
							<input type='text' placeholder='Objet' name='objet' class='form-control'>
					</div>
					<div class='col-md-6'>
							<textarea class='form-control' rows='7' name='contenu' placeholder='Votre message'></textarea>
							<button type='submit' class='btn btn-primary' style='padding:0;margin-top:20px;width:200px;text-align:left;'>
								<span style='display:block;padding: 0 8px 0 8px;;/* margin-left:40px; */height:38px;line-height:38px;margin-right: 60px;float: right;'>Envoyer</span>
								<span class='glyphicon glyphicon-ok' style='float:left;width:40px;height:38px;margin:0;padding: 0 0 0 8px;line-height:38px;font-size:1.5em;'></span>
							</button>
					</div>
				</form>
			</div>
			<div class='row' style='margin-top:40px;'>
				
				<div class='col-md-6'>
					<p class='address'>
							77, rue du Bret<br>
							38090 Villefontaine<br>
							France<br>
							<br>
							112, rue Dedieu<br>
							69100 Villeurbanne<br>
							France<br>
							<br>
							email : contact@parions-potes.fr<br>
							<br>
							</p><ul class='social'>
								<li class='googleplus'><a href='https://plus.google.com/+LumApps'>Google+</a></li>
								<!--li class='linkedIn'><a href='#'>LinkedIN</a></li-->
								<!--li class='facebook'><a href='#'>facebook</a></li-->
								<li class='youtube'><a href='http://www.youtube.com/channel/UC5eRwsgghxoAhdKvHWDeZzw'>youtube</a></li>
							</ul>
					<p></p>
				</div>
			</div>
		</div>
	</div>";

?>