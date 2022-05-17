<?php
require_once("mesFonctions.php");
initMenu();

echo "<fieldset><legend >Historique</legend>";
$file="fichier.csv";
if (file_exists($file)){
	$fp = fopen($file,'r'); //ouverture du fichier
	$res = fgetcsv($fp);

	$nbr_entete = count($res);
	//echo $num;
	
	//mise en page entête
	echo "<table border='2' bordercolor='444444' cellpadding = '7' cellspacing='2' align = center>";
	echo "<tr>";

	for ($i=0; $i< $nbr_entete-2; $i++){
		echo "<th bgcolor='e5e5e5'>".$res[$i]."</th>";
	}
	echo "</tr>";
	
	//mise en page reste des données
	while($res = fgetcsv($fp)){
		$donne = count($res);
		$compteur = 0;
		echo "<tr>";
		for ($i=0; $i< $donne-2; $i++){
			if ($res[$i] != null){
				$compteur += 1;
				if ($compteur == 3){
					for ($i=0; $i< $donne-2; $i++){
						echo "<th bgcolor='e5e5e5'>".$res[$i]."</th>";
					}
				
				}
			}
		}
		echo "</tr>";
	}
	echo "</table>";
	fclose($fp); //fermeture du fichier
}
else
	die("Fichier inexistant");

echo "</fieldset>";
?>
