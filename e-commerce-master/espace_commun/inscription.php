<?php
/* FORMULAIRE D'INSCRIPTION */
session_start();

require('../src/log.php');
require "../PHPMailer/PHPMailerAutoload.php";



if(isset($_POST["ok"])){

	$cle = rand(1000000,9000000);
	$_SESSION['cle'] = $cle;

	foreach ($_POST as $k => $v) $$k = $v;
	
	if(!empty($email) && !empty($password) && !empty($password_two) 
		&& !empty($prenom) && !empty($nom) && !empty($birthDate)
		&& !empty($adresse1) && !empty($adresseLivraison) && !empty($pays) && !empty($ville)  
		&& !empty($codePostal) && !empty($numero) && !empty($civilite) && !empty($captcha)){

		require('../src/connect.php');

		// VARIABLES
		$email 				= htmlspecialchars($email);
		$password 			= htmlspecialchars($password);
		$password_two		= htmlspecialchars($password_two);

		// Captcha valide
		if ($_POST['captcha'] != $_SESSION['captcha']){
			header('location: inscription.php?error=1&message=Votre captcha est faux.');
			exit();
		}

		// PASSWORD = PASSWORD TWO
		if($password != $password_two){

			header('location: inscription.php?error=1&message=Vos mots de passe ne sont pas identiques.');
			exit();

		}

		// PASSWORD_TWO VERIFICATION STRONG
		function checkPassword($pwd) {
			if( strlen($pwd) < 8 ) {
				header('location: inscription.php?error=1&message=Mot de passe trop court.');
				exit();
			}

			if( !preg_match("#[0-9]+#", $pwd) ) {
				header('location: inscription.php?error=1&message=Mot de passe doit inclure au moins 1 chiffre.');
				exit();
			}

			if( !preg_match("#[a-z]+#", $pwd) ) {
				header('location: inscription.php?error=1&message=Mot de passe doit inclure au moins 1 minuscule.');
				exit();
			}

			if( !preg_match("#[A-Z]+#", $pwd) ) {
				header('location: inscription.php?error=1&message=Mot de passe doit inclure au moins 1 majuscule.');
				exit();
			}
				

			if( !preg_match("#\W+#", $pwd) ) {
				header('location: inscription.php?error=1&message=Mot de passe doit inclure au moins 1 caractère spécial.');
				exit();
			}
				
			
		}
		checkPassword($password);


		function checkMail($email) {

            // ADRESSE EMAIL VALIDE
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

                header('location: inscription.php?error=1&message=Votre adresse email est invalide.');
                exit();

            }

		}

		checkMail($email);

        // EMAIL DEJA UTILISEE
        $req = $db->prepare("SELECT count(*) as numberEmail FROM membres WHERE email = ?");
        $req->execute(array($email));

        while($email_verification = $req->fetch()){

            if($email_verification['numberEmail'] != 0){

                header('location: inscription.php?error=1&message=Cette adresse email est déjà utilisée par un autre utilisateur.');
                exit();

            }

        }

		//idclient 
		$num_random = rand(0,999);
		$idclient = $nom[0]."-".$num_random;
		echo $idclient;

		// HASH
		$secret = sha1($email).time();
		$secret = sha1($secret).time();

		// CHIFFRAGE DU MOT DE PASSE
		$password = "aq1".sha1($password."123")."25";




		function smtpmailer($to, $from, $from_name, $subject, $body)
			{
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->SMTPAuth = true; 
		
				$mail->SMTPSecure = 'ssl'; 
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 465;  
				$mail->Username = 'testpourprojet92@gmail.com';
				$mail->Password = 'Krishnath92';   
		
		//   $path = 'reseller.pdf';
		//   $mail->AddAttachment($path);
		
				$mail->IsHTML(true);
				$mail->From="testpourprojet92@gmail.com";
				$mail->FromName=$from_name;
				$mail->Sender=$from;
				$mail->AddReplyTo($from, $from_name);
				$mail->Subject = $subject;
				$mail->Body = $body;
				$mail->AddAddress($to);

				if(!$mail->Send())
				{
					$error ="Please try Later, Error Occured while Processing...";
					return $error; 
				}
				else 
				{
					$error = "Thanks You !! Your email is sent.";  
					return $error;
				}

			}
			
			$to   = 'receveurprojet92@gmail.com';
			$from = 'testpourprojet92@gmail.com';
			$name = 'Test';
			$subj = 'Confirmation email';
			$msg = 'Pour finaliser votre inscriptions, voici le code que vous devrez rentrer : '.$cle;
			
			$error=smtpmailer($to,$from, $name ,$subj, $msg);

			$info = array(
				'idclient'     =>  $idclient,
				'email'     =>  $email,
				'password'     =>  $password,
				'secret'    =>  $secret,
				'prenom'   =>  $prenom,
				'nom'   =>  $nom,
				'birthDate'  =>  $birthDate,
				'adresse1'     =>  $adresse1,
				'adresseLivraison'     =>  $adresseLivraison,
				'pays'    =>  $pays,
				'ville'   =>  $ville,
				'codePostal'   =>  $codePostal,
				'numero'    =>  $numero,
				'civilite'   =>  $civilite,
				);
				$_SESSION["info"][0] = $info;

			header('location: verif.php?id='.$idclient.'');

	}


}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Stunning outfit Shop</title>
	<!--meta name="viewport" content="width=device-width"-->
	<link rel="stylesheet" type="text/css" href="../design/formulaires.css">
	<link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>

	<header>
      	<div id="brand">
         	<a href= "accueilCommun.php?accueil=1" ><img src="../img/logo.png" alt="LOGO" /></a>
      	</div>
   	</header>
	
	<section>
		<div id="login-body">
			<h1>Devenir membre</h1>
			<p>
				Devenez membre pour ne plus passer à côté des promotions, 
				des offres, des remises et des bons de réduction. 
			</p>

			<?php if(isset($_GET['error'])){

				if(isset($_GET['message'])) {

					echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

				}

			} else if(isset($_GET['success'])) {

				echo'<div class="alert success">Vous êtes désormais inscrit. <a href="connexion.php">Connectez-vous</a>.</div>';

			} ?>

			<form method="post" action="inscription.php" >

			    <!--Civilité-->
			    <div class="input"><br>
					<label for="civilité" class="form-label">Civilité</label>
					<select name="civilite" class="form-select" id="civilite" required>
						<option value="">Choisir...</option>
						<option>Mr</option>
						<option>Mme</option>
					</select>
				</div>
				<!--Prénom-->
				<div class="input"><br>
					<label >Prénom</label>
              		<input name="prenom" type="text" placeholder="Prénom" required autofocus>
				</div>
				<!--Nom-->
				<div class="input">
					<label for="lastName" class="form-label">Nom de famille</label>
              		<input name="nom" type="text" id="lastName" placeholder="Nom de famille"  required>
				</div>
				<!--Date Naissance-->
				<div class="input">
					<label for="date" class="form-label">Date de naissance </label>
              		<input name="birthDate" type="date" placeholder="Date de naissance">
			  	</div><br>

				<!--Adresse Principale-->
				<div class="input">
					<label for="address" class="form-label">Adresse</label>
					<input name="adresse1" type="text" id="address" placeholder="1234 Main St" required>	
            	</div>
				
				<!--Deuxième adresse-->
				<div class="input">
					<label >Complément adresse <span style="font-size: 0.8em;">(Optionel)</span></label>
					<input name="adresse2" type="text" id="address" placeholder="1234 Main St" >	
            	</div>

				<!--Adresse Livraison-->
				<div class="input">
					<label for="address" class="form-label">Adresse de livraison</label>
					<input name="adresseLivraison" type="text" id="address" placeholder="1234 Main St" required>	
            	</div>

				<!--Number-->
				<div class = "input">
					<label for="zip" class="form-label">Numéro</label>
					<input type="text" name="numero" placeholder="Votre numéro" required />
				</div>

				<!--Pays-->
				<div class="input"><br>
					<label for="country" class="form-label">Pays</label>
              		<input name="pays" type="text" placeholder="Pays" required >
				</div>
		

				<!--Ville-->
				<div class="input"><br>
					<label for="state" class="form-label">Ville</label>
              		<input name="ville" type="text" placeholder="state" required >
				</div>

				<!--Code postal -->
				<div class="input">
					<label for="zip" class="form-label">Code postal</label>
					<input name="codePostal" type="text" class="form-control" id="zip" placeholder="92500" required>
				</div>

				<!--Email-->
				<div class = "input">
					<label for="zip" class="form-label">Email</label>
					<input type="email" name="email" placeholder="Votre adresse email" required />
				</div>

				<!--Mot de passe-->
				<div class = "input">
					<i>
						<p>Votre mot de passe doit contenir au moins: <br/>
							<span id="minuscule" class="invalid">une minuscule</span> /
							<span id="majuscule" class="invalid">une majuscule</span> /
							<span id="chiffre" class="invalid">un chiffre</span> /
							<span id="special" class="invalid">un caractère spécial ($@!%*#/\&).</span> 
							<span id="longueur" class="invalid">Et faire au moins 8 caractères</span> 
						</p>
					</i>
					<label for="zip" class="form-label">Mot de passe</label>
					<input type="password" name="password" id = "pwd" placeholder="Mot de passe" required />
					<input type="checkbox" onclick = "Afficher()"> Afficher
						<br/><br/>
					<!--img src="img/close_eye.png" id = "eye" onClick="changer()" /-->
				</div>
			
				<!--Confirmation mot de passe-->
				<input type="password" name="password_two" id = "pwd2" placeholder="Retapez votre mot de passe" required />
				<input type="checkbox" onclick = "Afficher2()"> Afficher <br/><br/>

				<!--Captcha-->
				<div class="input">
					<img src="captcha.php" width="60" height="50"/>
					<input name="captcha" type="text" id="captcha" placeholder="Rentrez le nombre" required>	
            	</div>

				<button type="submit" name = "ok" class>S'inscrire</button>
			</form>


			<p class="grey">Déjà membre ? <a href="connexion.php">Connectez-vous</a>.</p>	<br>	
			<button style="width: 40%; padding: 5px; font-size: 0.85em" onclick="window.location.href='accueilCommun.php';">Revenir à l'accueil</button>
		</div>
	</section>

	<?php include('../src/boutiqueFooter.php'); ?>
	<script src ="../js/script.js"> </script>
</body>
</html>

