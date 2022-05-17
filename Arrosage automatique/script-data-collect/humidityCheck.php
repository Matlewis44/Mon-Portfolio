#!/usr/bin/env php
<?php
while(1){
	if(date("i") == 0) {
		$date = date("Y/m/d");
		$heure = idate("H");
		$humidite = shell_exec("python /var/www/html/send.py getHumidity");
		echo "date : " . $date . "\n";
		echo "heure : " . $heure . "\n";
		echo "humidité : " . $humidite . "\n";
		require("parametres.php");

		// connexion au serveur MySQL
		$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");
		// Utiliser la bd souhaitée
		$bd="projet-arrosage";
		mysqli_select_db($connexion,$bd) or die ("Impossible d'utilise la bd souhaitée");

		// Quelle est la table à consulter + Requete à faire
		$table="dataCollect";
		$requete="INSERT INTO `dataCollect`(`date`, `heure`, `humidité`) VALUES ('$date','$heure','$humidite')";
		$resultat=mysqli_query($connexion,$requete) or die ("Commande failed");
		mysqli_close($connexion);
		echo "La base de données a été mise à jour.\n";
	}
	else
		$heure = idate("H");
		echo "Heure :" . date("H:i") . ". Dernière mise à jour à: " . $heure . ":00\n";
	sleep(60);
}
?>
