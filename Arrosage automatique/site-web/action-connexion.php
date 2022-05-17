<?php
//parametres de connexion
require("parametres.php");

//connexion au serveur MySQL
$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");

//Utiliser la bd souhaitée
$bd="projet-arrosage";
mysqli_select_db($connexion, $bd) or die("Impossible d'utiliser la bd souhaitée");

//Quelle est la table à consulter + Requête à faire
$table="utilisateurs";

$requete="SELECT login, password, groupe FROM $table";
$resultat=mysqli_query($connexion,$requete);
$return = 1;
session_start();
if (isset($_SESSION['groupe']))
	header('Location: index.php?error=409');
else if (isset($_GET['login'],$_GET['password'])){
	foreach($_GET as $k => $val){
	$$k=$val;
	}
	
	$passwd = md5($password);
	
	while($ligne=mysqli_fetch_array($resultat)) {
		if (($login==$ligne[0]) && ($passwd==$ligne[1])) {
			//redirection vers la page
			
			$_SESSION['login']=$login;
			$_SESSION['groupe']=$ligne[2];
			$_SESSION['date']=time();
			if($ligne[2]=="dev"){
				header('Location: accueil.php'); //ou administration
			} else if ($ligne[2]=="usr"){
				header('Location: accueil.php'); //ou interface
			}
			$return = 0;
		}
	}

    //redirection vers le formulaire et envoie du message erreur
	if($return != 0){
		header('Location: index.php?error=400');
	}
}
?>
