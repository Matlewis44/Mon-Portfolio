<?php

session_start();

if(isset($_POST['supprimer'])){
	if(isset($_SESSION['groupe'])){
		
		if (strcmp($_SESSION['groupe'],"dev")==0){

			foreach($_POST as $k => $v){
				$$k= $v;
			}
			$utilisateurCible = $nomUtilisateur;
			
			require("parametres.php");

			$connexion=mysqli_connect($serveur,$log,$pwd);

			$bd="projet-arrosage";
			mysqli_select_db($connexion, $bd);

			$table="utilisateurs";
			
			$requete="DELETE FROM `utilisateurs` WHERE `login`='$utilisateurCible'";
			$resultat=mysqli_query($connexion,$requete);
			header('Location: administration.php?success=deleted');
		}
		else
			header('Location: index.php?error=403');
	}
	else{
		header('Location: index.php?error=400');
	}
}
else {
	header('Location: administration.php?error=impossible');
}

?>