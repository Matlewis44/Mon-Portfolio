<?php
session_start();
require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0 or strcmp($groupe,"usr")==0){
		initMenu("<a href='accueil.php'>Accueil</a> > Configuration système");
		
		echo"<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle4.css' />";
		echo "<h2 style='padding-left: 30px; color:#404040;'>| Configuration système |</h2>";
		if(isset($_GET['error'])){
			$error = $_GET['error'];
			if ($error == "checkchamps") 
				$msg_error = "Configuration non créée! Il faut laisser un champs vide (soit fréquence, soit seuil).";
			else if ($error == "alreadyexists") 
				$msg_error = "Configuration non créée! Le nom de cette configuration existe déjà.";
			else if ($error == "seuilrange") 
				$msg_error = "Configuration non créée! Le seuil doit être compris entre 20 et 70.";
			else if ($error == "absoluteval")
				$msg_error = "Configuration non créée! Les valeurs doivent être possitives et non nulles.";
			else if ($error == "forbiddenname")
				$msg_error = "Configuration non créée! Nom de configuration interdit.";
			else if ($error == "namelength")
				$msg_error = "Configuration non créée! Le nom de la configuration doit faire au moins 3 caractères.";
			else if ($error == "invalidname")
				$msg_error = "Configuration non créée! Le nom de la configuration ne doit pas contenir d'espace.";
			else if ($error == "unknown") //pour la bd (action-supprimer-config.php)
				$msg_error = "Configuration non supprimée! Cette configuration n'existe pas dans la base de données.";
			echo "<div style='padding: 2px;color: #871707; border: 1px solid #871707; background-color: #f6d9d8;
			margin-top: 10px; margin: 20px;
	padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_error."</div>"; 
		}
		else if(isset($_GET['success'])){
			$success = $_GET['success'];
			if ($success == "removed")
				$msg_success = "Configuration supprimée avec succès!";
			echo "<div style='padding: 2px;color: #0d3a78; border: 1px solid #0d3a78; background-color: #c2e0ed;
			margin-top: 10px; margin: 20px;
	padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_success."</div>"; 
		}
		echo "<div id='configuration-systeme'>";


		if(isset($_POST['statut'])){
			$statut = $_POST['statut'];
			if ($statut == "RESET") {
				$configuration = shell_exec("python /var/www/html/send.py resetConfig");
			}
		}
		echo "
		<h3>Configuration actuelle</h3>";
		$configuration = shell_exec("python /var/www/html/send.py getConfig"); #getConfig
		echo "<p style='marging-bottom:10px;'>" . $configuration . "</p>";
		echo "<form method='post'><input type='submit' value='RESET' name='statut' id='valider'></form>
		<hr width='280px' align='left'>";

		echo "<h3>Appliquer configuration</h3>";
		echo "<form action='action-setConfig.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Nom: </span></td> <td> <input type='text' name='configuration'></td>
			</tr>
		</table>
		<table>
			<tr>
				<td> <input type='submit' name='ok' value='Appliquer' id='valider'></td>
				<td> <input type='reset' name='reset' value='Annuler' id='annuler'></td>
			</tr>
		</table>
		</form>
		<hr width='280px' align='left'>";

		echo "<h3>Créer une configuration</h3>";
		echo "<form action='action-creation-config.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Nom: </span></td>
				<td> <input type='text' style='width:180px;' name='nom'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Fréquence: </span></td>
				<td> <input placeholder='en heures' type='number' min='1' max='48' style='width:180px;' name='fréquence'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Seuil: </span></td>
				<td> <input placeholder='en %' type='number' min='20' max='70' style='width:180px;' name='seuil'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Durée: </span></td> 
				<td> <input placeholder='en secondes' type='number' min='1' max='60' style='width:180px;' name='durée'></td>
			</tr>
		</table>
		<table>
			<tr>
				<td> <input type='submit' name='ok' value='Créer' id='valider'></td>
				<td> <input type='reset' name='reset' value='Annuler' id='annuler'></td>
			</tr>
		</table>
		</form>
		<hr width='280px' align='left'>";
		
		echo "<h3>Supprimer une configuration</h3>";
		echo "<form action='action-supprimer-config.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Nom: </span></td> <td> <input type='text' name='configuration'></td>
			</tr>
		</table>
		<table>
			<tr>
				<td> <input type='submit' name='ok' value='Supprimer' id='valider'></td>
				<td> <input type='reset' name='reset' value='Annuler' id='annuler'></td>
			</tr>
		</table>
		</form>";
		echo "<hr width='280px' align='left'><h3>Configurations existantes</h3>";
		require("parametres.php");

		// connexion au serveur MySQL
		$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");


		// Utiliser la bd souhaitée
		$bd="projet-arrosage";
		mysqli_select_db($connexion,$bd) or die ("Impossible d'utilise la bd souhaitée");

		// Quelle est la table à consulter + Requete à faire
		$table="configurations";
		$requete="SELECT * FROM $table order by nom";
		$resultat=mysqli_query($connexion,$requete);

		//lecture de résultats
		echo "<table border='1', cellpadding='4', cellspacing='0', style='vertical-align: top;'>
		<tr>
			<th><span id='texte-noir'>Nom</span></th>
			<th><span id='texte-noir'>Fréquence</span></th>
			<th><span id='texte-noir'>Seuil</span></th>
			<th><span id='texte-noir'>Durée</span></th>
		</tr>";

		while($ligne=mysqli_fetch_row($resultat)){
		echo "<tr>";
		$col=0;
		foreach($ligne as $v) {
			if($v == 0 and ($col == 1 or $col==2))
				$v = "--";
			else if($col==1)
				$v = $v.'h';
			else if($col==2)
				$v = $v.'%';
			else if($col==3)
				$v = $v.'s';
			echo "<td><span id='texte-noir'>".$v."</span></td>";
			$col = $col+1;
		}
		echo "</tr>";
		}
		echo "</table>";
		echo"</div>";
		
		mysqli_close($connexion);
		initFin();
	}
	else
		header('Location: index.php?error=403');
}
else
	header('Location: index.php?error=401');
	
?>
