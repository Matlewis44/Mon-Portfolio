<?php
session_start();
require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0){
		initMenu("<a href='accueil.php'>Accueil</a> > Administration");
		
		echo "
		<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle4.css' />
		<h2 style='padding-left: 30px; color:#404040;'>| Administration |</h2>";
		if(isset($_GET['error'])){
			$error = $_GET['error'];
			if ($error == "alreadyexists")
				$msg_error = "Utilisateur non ajouté! Ce nom d'utilisateur existe déjà.";
			else if ($error == "passwddifferents")
				$msg_error = "La confirmation du mot de passe est différentes du nouveau mot de passe saisi.";
			else if ($error == "passwdlength")
				$msg_error = "Utilisateur non ajouté! Le mot de passe doit faire au moins 4 caractères.";
			else if ($error == "loginlength")
				$msg_error = "Utilisateur non ajouté! Le login doit faire au moins 3 caractères.";
			else if ($error == "incomplets")
				$msg_error = "Utilisateur non ajouté! Tous les champs doivent être remplis.";
			else if ($error == "impossible")
				$msg_error = "Impossible d'accéder à la requête.";
			echo "<div style='padding: 2px;color: #871707; border: 1px solid #871707; background-color: #f6d9d8;
			margin-top: 10px; margin: 20px;
	padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_error."</div>"; 
		}
		if(isset($_GET['success'])){
			$success = $_GET['success'];
			if ($success == "added")
				$msg_success = "Utilisateur ajouté avec succès!";
			else if ($success == "modified")
				$msg_success = "Mot de passe modifié avec succès!";
			else if ($success == "deleted")
				$msg_success = "Utilisateur supprimé avec succès!";
			echo "<div style='padding: 2px;color: #0d3a78; border: 1px solid #0d3a78; background-color: #c2e0ed;
			margin-top: 10px; margin: 20px;
			padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_success."</div>"; 
		}
		echo "<div id='configuration-systeme'>";
		echo "
		<h3><span id='texte-noir'>Contrôle direct</span></h3>";
		echo "
		<table>
			<tr>
				<td><form method='post'><input type='submit' value='ON' name='statut' id='valider1'></form></td>
				<td><form method='post'><input type='submit' value='OFF' name='statut' id='annuler1'></form></td>
			</tr>
		</table>";
		echo "<hr width='340px' align='left'>
		<h3><span id='texte-noir'>Ajout d'un utilisateur</span></h3>";
		echo "
		<form action='action-insertion.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Login: </span></td> <td> <input type='text' name='login'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Mot de passe: </span></td> <td> <input type='text' name='password'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Groupe: </span></td>
				<td> <span id='texte-noir'><input type= 'radio' name='groupe' value='dev'> développeur
				<input type= 'radio' name='groupe' value='usr'> utilisateur</span></td>
			</tr>
		</table>
		<table>
			<tr>
				<td><input type='submit' name='ok' value='Valider' id='valider1'></td>
				<td><input type='reset' name='reset' value='Annuler' id='annuler1'></td>
			</tr>
		</table>
		</form>";
		if(isset($_POST['statut'])){
			$statut = $_POST['statut'];
			if($statut == "ON") {
				$configuration = shell_exec("python /var/www/html/send.py resetConfig");
				$output = shell_exec("python /var/www/html/send.py A"); #A = Allumer
			}
			else if ($statut == "OFF") {
				$configuration = shell_exec("python /var/www/html/send.py resetConfig");
				$output = shell_exec("python /var/www/html/send.py E"); #E = Eteindre
			}
		}
	
		require("parametres.php");
		
		// connexion au serveur MySQL
		$connexion=mysqli_connect($serveur,$log,$pwd) or die("Impossible de se connecter au serveur MySQL");


		// Utiliser la bd souhaitée
		$bd="projet-arrosage";
		mysqli_select_db($connexion,$bd) or die ("Impossible d'utiliser la bd souhaitée");

		// Quelle est la table à consulter + Requete à faire
		$table="utilisateurs";
		$requete="SELECT login, password, groupe FROM $table order by login";
		$resultat=mysqli_query($connexion,$requete);

		//lecture de résultats
		echo "<hr width='340px' align='left'>
		<h3><span id='texte-noir'>Utilisateurs existants</span></h3>
		<table border='1', cellpadding='4', cellspacing='0', style='vertical-align: top;'>
			<tr>
				<th><span id='texte-noir'>Identifiant</span></th>
				<th><span id='texte-noir'>Groupe</span></th>
				<th><span id='texte-noir'>Modifier mot de passe</span></th>
				<th><span id='texte-noir'>Supprimer utilisateur</span></th>
			</tr>";

			$j = 1;
			while($ligne=mysqli_fetch_row($resultat)){
				echo "<tr>";
				echo "<td><span id='texte-noir'>".$ligne[0]."</span></td>";
				echo "<td><span id='texte-noir'>".$ligne[2]."</span></td>";
				echo "<td><span id='texte-noir'> 
							<form method='post' action='page-modification.php' style='margin: auto;'>
							<div id='boutons' style='text-align: center;'><input id='valider1' name='modifier' type='submit' value='modifier'/></div'>
							<input type='hidden' name='nomUtilisateur' value='$ligne[0]'>
							</form> 
						</span></td>";
				echo "<td><span id='texte-noir'>
							<form name='supprimerUtilisateur$j' method='post' action='action-supprimer-utilisateur.php' onsubmit='return verif($j);' style='margin: auto;'>
							<div id='boutons' style='text-align: center;'><input id='valider1' name='supprimer' type='submit' value='supprimer'  /></div>
							<input type='hidden' name='nomUtilisateur' value='$ligne[0]'>
							<input type='hidden' name='numero' value='$j'>
							</form>
						</span></td>";
				echo "</tr>";
				$j++;
			}
		echo "</table>";

		mysqli_close($connexion);
		echo"</div>";
		
		initFin();
	}
	else
		header('Location: index.php?error=403');
}
else
	header('Location: index.php?error=401');
	
?>
