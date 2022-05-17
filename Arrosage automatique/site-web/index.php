<?php
session_start();

if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0 or strcmp($groupe,"usr")==0){
		header('Location: accueil.php');
	}
}
else{
	require 'mesFonctions.php';
	initMenu("<a href='index.php'>Authentification</a>");

	/* Body */
	echo "
	<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle4.css' />

	<h2><span id='texte-noir'>|Authentification|</span></h2>";
	if(isset($_GET['error'])){
		$error = $_GET['error'];
		$error = $_GET['error'];
		if ($error == 400) $msg_error = "Erreur d'authentification, veuillez réessayer.";
		else if ($error == 401) $msg_error = "Accès refusé, veuillez vous connecter.";
		else if ($error == 403) $msg_error = "Accès refusé, vous n'avez pas les permissions.";
		else if ($error == 409) $msg_error = "Veuillez d'abord vous déconnecter.";
		else $msg_error = "Erreur inconnue";
		echo "<div style='padding: 2px;color: #871707; border: 1px solid #871707; background-color: #f6d9d8;margin-top: 10px; margin: 20px;
	padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_error."</div>"; 
		}
	echo "<div id='configuration-systeme'>
	<div id=index>
		<h3 style='margin-bottom: 10px;'><span style='color: #30a5ff'>AWS</span><span id='texte-noir'> - Connexion</span></h3>
		<form method='get' action='action-connexion.php' style='margin-bottom:8px;'>
		<table cellpadding='4'>
			<tr>
			<td><span id='texte-noir'>Nom d'utilisateur :</span></td> <td><input style='width:230px;' name='login' type='text' placeholder=''/></td>
		</tr>
		<tr>
			<td><span id='texte-noir'>Mot de passe :</span></td> <td> <input style='width:230px;' name='password' type='password' placeholder=''/></td>
		</tr>
		</table>
		<div class='button'>
			<button name='ok' id='valider1' type='submit'>Connexion</button>
			<button name='rst' id='annuler1' type='reset'>Annuler</button>
		</div>
	</div>";
	
	echo "</div>";

	initFin();
}

?>
