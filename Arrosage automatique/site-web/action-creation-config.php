<?php
if(isset($_POST['nom'], $_POST['durée'])){
	if(isset($_POST['fréquence']) and isset($_POST['seuil'])){
			$nom = $_POST['nom'];
			$duree = $_POST['durée'];
			$return = 0;
			if($_POST['seuil'] == Null){
				$frequence = $_POST['fréquence'];
				$seuil = 0;
				if($frequence < 1){
					header('location: interface.php?error=absoluteval');
					$return = 1;
				}
			}
			else if ($_POST['fréquence'] == Null){
				$frequence = 0;
				$seuil = $_POST['seuil'];
				if($seuil < 20 or $seuil > 70){
					header('location: interface.php?error=seuilrange');
					$return = 1;
				}
			}
			else{
				header('location: interface.php?error=checkchamps');
				$return = 1;
			}
			if($return != 1 && $duree < 1){
				header('location: interface.php?error=absoluteval');
				$return = 1;
			}
			if($return != 1 && strlen($nom) < 3){
				header('location: interface.php?error=namelength');
				$return = 1;
			}
			if($return != 1 && strpos($nom," ")){
				header('location: interface.php?error=invalidname');
				$return = 1;
			}
			if($return != 1 && $nom == "Aucune"){
				header('location: interface.php?error=forbiddenname');
				$return = 1;
			}
			if($return!=1){
				require("parametres.php");
				// connexion au serveur MySQL
				$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");


				// Utiliser la bd souhaitée
				$bd="projet-arrosage";
				mysqli_select_db($connexion,$bd) or die ("Impossible d'utilise la bd souhaitée");

				// Quelle est la table à consulter + Requete à faire
				$table="configurations";
				$requete="SELECT nom FROM $table";
				$resultat=mysqli_query($connexion,$requete);
				while($ligne=mysqli_fetch_array($resultat)) {
					if ($nom==$ligne[0]) {
						header('Location: interface.php?error=alreadyexists');
						$return = 1;
					}
				}
				mysqli_close($connexion);
			}
			if($return!=1){ //ajout à la bd

				// connexion au serveur MySQL
				$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");


				// Utiliser la bd souhaitée
				$bd="projet-arrosage";
				mysqli_select_db($connexion,$bd) or die ("Impossible d'utilise la bd souhaitée");

				// Quelle est la table à consulter + Requete à faire
				$table="configurations";
				$requete="INSERT INTO $table VALUES ('$nom','$frequence','$seuil','$duree')";
				$resultat=mysqli_query($connexion,$requete);
				mysqli_close($connexion);
				header('Location: interface.php');
			}

	}
}
?>

