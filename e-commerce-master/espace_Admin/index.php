<?php
/*
if(isset($_POST['valider'])){
	if(!empty($_POST['mail']) AND !empty($_POST['mdp'])){
		
		// VARIABLES
		$mail_saisi=htmlspecialchars($_POST['mail']);
		$mdp_saisi = htmlspecialchars($_POST['mdp']);

		$mail_ParDefaut = "admin@gmail.com";
		$mdp_ParDefaut = "admin1234";

		if($mail_saisi == $mail_ParDefaut AND $mdp_saisi == $mdp_ParDefaut){
			$_SESSION['mdp'] = $mdp_saisi;
			header('location: accueil.php?success=1');
		}else{
			header("location: index.php?error=1&message=Votre-mot-de-passe-ou-nom-est-incorrect");
		}

	}else{
		header("location: index.php?error=1&message=Veuillez compléter tous les champs...");
	}
}*/
require('../src/connect.php');
session_start();

if(isset($_POST['valider'])){
	if(!empty($_POST['mail']) AND !empty($_POST['mdp'])){
		
		// VARIABLES
		$mail_saisi=htmlspecialchars($_POST['mail']);
		$mdp_saisi = htmlspecialchars($_POST['mdp']);

		$mail_ParDefaut = "admin@gmail.com";
		$mdp_ParDefaut = "admin1234";

		if($mail_saisi == $mail_ParDefaut AND $mdp_saisi == $mdp_ParDefaut){
			$_SESSION['mdp'] = $mdp_saisi;
			$_SESSION['admin'] = "admin";
			header('location: ../espace_commun/accueilCommun.php?accueil=1&success=1');
		}else{
			header("location: index.php?error=1&message=Votre-mot-de-passe-ou-nom-est-incorrect");
		}

	}else{
		header("location: index.php?error=1&message=Veuillez compléter tous les champs...");
	}
}
		
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../design/formulaires.css" >
	<link rel="stylesheet" href="../design/footerFormulaires.css" >
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
	<title>Connexion Administrateur</title>
</head>
<body>
	<div id="brand" ><img style="height: 55px" src="img/logo.png" alt=""></div>
	
	<section>
		<div id="login-body">
			<?php if(isset($_GET['error'])){

				if(isset($_GET['message'])) {

					echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

				}

				} else if(isset($_GET['success'])) {

				echo'<div class="alert success">Vous êtes désormais inscrit. <a href="connexion.php">Connectez-vous</a>.</div>';

				} ?>
								
			<h1>S'authentifier</h1>

			<form method="POST" action="index.php">
				<input type="email" name="mail" placeholder="mail Administrateur" autofocus/><br>
				<input id ="pwd" type="password" name="mdp" placeholder="Mot de passe"/>
				<input type="checkbox" onclick = "Afficher()"> Afficher<br/><br/>
				
				<button type="submit" name="valider">connexion</button>

			</form>
			<br>	
			<button style="width: 40%; margin-top: 25px; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_commun/accueilCommun.php?accueil=1';">Retourner à l'accueil</button>

			<!--button style="width: 40%; padding: 5px; font-size: 0.85em; " onclick="window.location.href='accueil.php';">Revenir à l'accueil</button-->
		</div>
	</section>

	<?php include("../src/boutiqueFooter.php"); ?>

	<script src="../js/script.js"></script>
</body>
</html>