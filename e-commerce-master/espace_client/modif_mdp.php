<?php
	/* FORMULAIRE DE MODIFICATION */

	session_start();

	/* DOIT ETRE CONNECTE SINON  */
	if(!isset($_SESSION["user"])){
			header('Location: ../espace_commun/connexion.php?error=1&message=Connectez-vous.');
			exit();
	}

	require('../src/log.php');

	if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_two'])){

		require('../src/connect.php');

		// VARIABLES
		$email 				= htmlspecialchars($_POST['email']);
		$password 			= htmlspecialchars($_POST['password']);
		$password_two		= htmlspecialchars($_POST['password_two']);

		// ADRESSE EMAIL SYNTAXE
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

			header('location: modif_mdp.php?error=1&message=Votre adresse email est invalide.');
			exit();

		}
		// EMAIL DEJA UTILISE
		$req = $db->prepare("SELECT count(*) as numberEmail FROM membres WHERE email = ?");
		$req->execute(array($email));

		while($email_verification = $req->fetch()){
			if($email_verification['numberEmail'] != 1){
				header('location: modif_mdp.php?error=1&message=Cette adresse email est déjà utilisée.');
				exit();
			}
		}


		// PASSWORD_TWO VERIFICATION STRONG
		function checkPassword($pwd) {
			if( strlen($pwd) < 8 ) {
				header('location: modif_mdp.php?error=1&message=Mot de passe trop court.');}
				exit();		

			if( !preg_match("#[0-9]+#", $pwd) ) {
				header('location: modif_mdp.php?error=1&message=Mot de passe doit inclure au moins 1 chiffre.');}
				exit();	

			if( !preg_match("#[a-z]+#", $pwd) ) {
				header('location: modif_mdp.php?error=1&message=Mot de passe doit inclure au moins 1 minuscule.');}
				exit();	

			if( !preg_match("#[A-Z]+#", $pwd) ) {
				header('location: modif_mdp.php?error=1&message=Mot de passe doit inclure au moins 1 majuscule.');}
				exit();	
				
			if( !preg_match("#\W+#", $pwd) ) {
				header('location: modif_mdp.php?error=1&message=Mot de passe doit inclure au moins 1 caractère spécial.');}
				
			 
		}
		checkPassword($password_two);

		// CHIFFRAGE DU MOT DE PASSE
		$password_two = "aq1".sha1($password_two."123")."25";

		

		// ENVOI
		$req = $db->prepare("SELECT * FROM membres WHERE email = ?");
		$req->execute(array($email));

		while($user = $req->fetch()){

			if($user['blocked'] ==0){

				$updateMdp = $db->prepare('UPDATE membres SET password = ? WHERE email = ?');
            	$updateMdp->execute(array($password_two,$email));

				header('location: modif_mdp.php?success=1');
				exit();

			}
			else {

				header('location: modif_mdp.php?error=1&Impossible-de-vous-authentifier-correctement.');
				exit();

			}

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
	<!--link rel="stylesheet" type="text/css" href="../design/boutiqueFooter.css"-->

	<link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>
	<header>
      	<div id="brand">
         	<a href="index.php" ><img src="../img/logo.png" alt="LOGO" /></a>
      	</div>
   	</header>

	<section>
		<div id="login-body">
				
			<h1>Changement de mot de passe</h1>

			
				<?php if(isset($_GET['error'])){

					if(isset($_GET['message'])) {

						echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

					}

					} else if(isset($_GET['success'])) {

					echo'<div class="alert success">Changement réussie. <a style="font-weight: bold;" href="connexion.php">Connectez-vous</a>.</div>';

					} 
				?>
				
			<form method="post" action="modif_mdp.php">
				<input type="email" name="email" placeholder="Votre adresse email" required autofocus/>
				<input id = "pwd" type="password" name="password" placeholder="Ancien mot de passe" required />
				<input type="checkbox" onclick = "Afficher()"> Afficher<br/><br/>

				<input type="password" name="password_two" id = "pwd2" placeholder="Nouveau mot de passe" required />
				<input type="checkbox" onclick = "Afficher2()"> Afficher <br/><br/>
				
				<button type="submit">valider</button>
				<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label><br/>
			</form>		

			<button style="width: 40%; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_commun/accueilCommun.php';">Revenir à l'accueil</button>

					
		</div>
	</section>

	<?php include("../src/boutiqueFooter.php"); ?>
	<script src="../js/script.js"> </script>
</body>
</html>