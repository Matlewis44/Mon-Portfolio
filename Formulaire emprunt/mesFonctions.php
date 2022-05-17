<?php
function initMenu(){
	echo "
		<link rel='stylesheet' href='stylesheet.css' />
		<title> Simulateur </title>
		<div style='width: 100%; height: 57px; background-color: #000000;'>
		<div id='navigation'>
			<ul>	
				<li><a href='simulation.php'>Simulation</a></li>
	
				<li><a href='historique.php'>Historique</a></li>
					
				<li><a href='admin.php'>Admin</a></li>

				<li><a href='README.html'>README</a></li>
			</ul>
		</div><!-- #navigation --></div>
	";
	echo "<div id=global>";
	echo "<h1 style='font-size:48px'>Emprunt bancaire</h1>";
}

function initStatut($user){
	echo "<table>
				<tr>
					<td><p style='text-align: left';>Bonjour <span style='color: #3030ea'>$user</span> !</p></td>
					<td style='padding-left: 600px'>
						<form method='post' action='action-deconnexion.php' style='padding-top: 15px;'>
						<div id='boutons'><input id='bouton' name='Deconnexion' type='submit' value='Déconnexion'/></div>
						</form>
					</td>
				</tr>
				</table>";
}

function supprcsv($csv){

	$row = 1;
	$handle = fopen('fichier.csv', 'r');
	while( !feof($handle) )
		{
			$line = fgets($handle);
			if (!($row == 2) )
				file_put_contents('fichier_final.csv', $line, FILE_APPEND);

		$row += 1;
		}
		fclose($handle);
		
		// Tu peux ensuite renommer fichier_final.txt si tu veux
		rename('fichier_final.csv', 'fichier.csv');

}


function nombrepositive ($liste){
 
    $positivenumber = 0;
	foreach ($liste as $value){
		if ((float)$value > 0){
			$positivenumber ++;
		} 
	    else {
			$positivenumber = 0;
		}
	}

	if ($positivenumber == 3){
		return true;
	}

	else {
		return false;
	}

}


function archivage($csv){

	//on initialise la date pour pouvoir la mettre dans le nom de l'archive
	$date = date('d-m-y');
	$handle = fopen('fichier.csv', 'r');
	$row = 1;
	if(file_exists('archive - '.$date.'.csv')){
		while( !feof($handle) )
		{
			$line = fgets($handle);
			if(!($row == 1)){
			file_put_contents('archive - '.$date.'.csv', $line, FILE_APPEND);
			}
			$row += 1;
		}
	}
	else{
		
		while( !feof($handle) )
		{
			$line = fgets($handle);
			file_put_contents('fichier_archivage.csv', $line, FILE_APPEND);
		}
	}
	
	
	
	// Ensuite on peut couper la connexion au fichier
	fclose($handle);
		
	// Tu peux ensuite renommer fichier_final.txt si tu veux
	rename('fichier_archivage.csv', 'archive - '.$date.'.csv');

}



?>