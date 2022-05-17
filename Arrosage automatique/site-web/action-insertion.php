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
$return = 0;
session_start();
if (!isset($_SESSION['groupe']))
	header('Location: index.php?error=403');
else if (isset($_POST['login'],$_POST['password'],$_POST['groupe'])){
	foreach($_POST as $k => $val){
	$$k=$val;
	}
	echo $login."<br/>";
	echo $password."<br/>";
	echo $groupe;
	while($ligne=mysqli_fetch_array($resultat)) {
		if ($login==$ligne[0]) {
			header('Location: administration.php?error=alreadyexists');
			$return = 1;
		}
	}
	mysqli_close($connexion);
	if($return != 1 && strlen($password) < 4){
		header('Location: administration.php?error=passwdlength');
		$return = 1;
	}
	if($return != 1 && strlen($login) < 3){
		header('Location: administration.php?error=loginlength');
		$return = 1;
	}
	if($return != 1){
		//connexion au serveur MySQL
		$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");
		
		//Utiliser la bd souhaitée
		$bd="projet-arrosage";
		mysqli_select_db($connexion, $bd) or die("Impossible d'utiliser la bd souhaitée");
		
		//Quelle est la table à consulter + Requête à faire
		$table="utilisateurs";
		
		$passwd = md5($password);
		
		$requete="INSERT INTO `utilisateurs`(`login`, `password`, `groupe`) VALUES ('$login','$passwd','$groupe')";
		$resultat=mysqli_query($connexion,$requete);
		mysqli_close($connexion);
		header('Location: administration.php?success=added');
	}
}
else{
	header('Location: administration.php?error=incomplets');
}
?>
