<?php
require_once("mesFonctions.php");
initMenu();

echo "
<fieldset style='margin-bottom:1em'><legend>Simulation</legend>
<form method='get' action=''>
<table align = center>
<tr><td>Somme à emprunter: </td><td><input type='int' name='C'style = 'border-radius:5px'/></td></tr>
<tr><td>Taux d'intéret: </td><td><input type='float' name='t'style = 'border-radius:5px'/></td></tr>
<tr><td>Période en mois: </td><td><input type='number' min = 1 name='n'style = 'border-radius:5px'/></td></tr>
<tr><td><input id='bouton' type='submit' name='ok' value='Valider'/> <input id='bouton' type='reset' name='res' value='Annuler'/></td></tr>
</table></fieldset>
</form>
";

$fileLines=file("fichier.csv");
$count = count($fileLines);


if (isset($_GET['ok'])){
	if (isset($_GET['C'], $_GET['t'], $_GET['n'])){
		$champ_vide=0;
		
		if (empty($_GET['C']))     $champ_vide=1;
		if (empty($_GET['t']))     $champ_vide=1;
		if (empty($_GET['n']))     $champ_vide=1;
		$C = $_GET['C'];
		$t = $_GET['t'];
		$n = $_GET['n'];
		$today = date("d.m.y");
		$ip = $_SERVER['REMOTE_ADDR'];
		$verifpositive = array($C,$t,$n);
		$validationpositive = nombrepositive ($verifpositive);

		if ($champ_vide == 0){

			if ($validationpositive == true){
				echo "<fieldset><legend>Résultat</legend>";
				$res = ($C*($t/12))/(1-(pow(1+($t/12),-$n)));
				$arrondi = round($res,2);
				echo "Mensualité: $arrondi €";
				echo "</fieldset>";
				//display($_GET);
			}

			else {
				echo "<fieldset><legend>Résultat</legend>";
				echo "Veuillez remplir tout les champs sans nombre négatif ";
				echo "</fieldset>";
				$arrondi = null;
			}
		
			
			
		}

		else {
		
			echo "<fieldset><legend>Résultat</legend>";
			echo "Veuillez remplir tout les champs";
			echo "</fieldset>";
			$arrondi = null;
		}

		$data = array($C,$t,$n,$arrondi,$today,$ip);
		if ($count <11){
			
			$csv = new SplFileObject('fichier.csv', 'a'); 
			$csv->fputcsv($data, ',');
		}
	
		if($count == 11){
			$csv2 =	supprcsv("fichier.csv");
			$csv = new SplFileObject('fichier.csv', 'a'); 
			$csv->fputcsv($data, ',');
		}


	}

}


?>