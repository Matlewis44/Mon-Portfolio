<?php
if(isset($_POST['configuration'])){
	$configuration = $_POST['configuration'];

	require("parametres.php");

	// connexion au serveur MySQL
	$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");
	// Utiliser la bd souhaitée
	$bd="projet-arrosage";
	mysqli_select_db($connexion,$bd) or die ("Impossible d'utilise la bd souhaitée");

	// Quelle est la table à consulter + Requete à faire
	$table="configurations";
	$requete="SELECT * FROM $table WHERE $table.nom = '$configuration'";
	$resultat=mysqli_query($connexion,$requete);
	mysqli_close($connexion);
	//var_dump($resultat);
	while($ligne=mysqli_fetch_row($resultat)){
		$trameBrut = "";
		foreach($ligne as $v) {
			$trameBrut = "$trameBrut"."-".$v;
		}
		//$trame = substr($trameBrut, 0, -1);
		$trame = "setConfig".$trameBrut;
		echo $trame;
		//shell_exec("python /var/www/html/sendOnly.py minute-45-0-15"); #envoi de la trame
		$output = shell_exec("python /var/www/html/send.py $trame"); #envoi de la trame
		echo "<br/>".$output;
		header('Location: interface.php?trame='.$trame.'&output='.$output);
	}
	echo "Configuration non existante...";
}

?>
