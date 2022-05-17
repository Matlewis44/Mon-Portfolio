<?php
if(isset($_POST['configuration'], $_POST['ok'])){
	$configuration = $_POST['configuration'];
	
	require("parametres.php");
	
	// connexion au serveur MySQL
	$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");

	// Utiliser la bd souhaitée
	$bd="projet-arrosage";
	mysqli_select_db($connexion,$bd) or die ("Impossible d'utiliser la bd souhaitée");

	// Quelle est la table à consulter + Requete à faire
	$table="configurations";
	
	$requete="SELECT nom FROM $table";
	$resultat=mysqli_query($connexion,$requete);
	
	$existe = false;
	while($ligne=mysqli_fetch_array($resultat) and $existe == false) {
		if ($configuration==$ligne[0]) {
			$existe = true;
		}
	}
	if($existe == true){
		$requete="DELETE FROM $table WHERE nom='$configuration'";
		mysqli_query($connexion,$requete);
		header('Location: interface.php?success=removed');
	}
	else{
		header('Location: interface.php?error=unknown');
	}
	
	mysqli_close($connexion);
}
else{
	header('Location: index.php?error=403');
}
?>

