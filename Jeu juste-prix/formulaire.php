<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="icon" type="image/png" href="../pictures/favicon2.png">
		<title>Jeu juste prix</title>
	</head>

</html>
<?php
	require_once("mesFonctions.php");
	
	initMenu();
			
			session_start();
			if(isset($_SESSION['user'])){
				$user = $_SESSION['user'];
				initStatut($user);
			}
			
			echo "<div id=cadre>";
			echo "Authentification";
			echo "
			<form method='post' action='action-authentification.php' style='margin: auto; padding-top: 10px; width: 450px;'>
			<table>
			<tr>
				<td style='padding-right: 100px;'>Login : </td>
				<td><input id='champs' name='log' type='text'></td>
			</tr>
			<tr>
				<td>Password : </td>
				<td><input id='champs' name='psw' type='password'></td>
			</tr>
			</table>
				<div id='boutons'><input id='bouton' name='ok' type='submit' valuer='Valider'>
				<input id='bouton' name='rst' type='reset' value='Annuler'></div>

			</form>
			";
			echo "</div>";

			if(isset($_GET['error'])) {
				$erreur = $_GET['error'];
				echo "<p style='color: #ff0000;'> $erreur </p>";
			}
			else if(isset($_GET['success'])) {
				$success = $_GET['success'];
				echo "<p style='color: #0000ff;'> $success </p>";
			}
			echo "Pas de compte? <a href='formulaire-ajout.php'>S'enregistrer</a>";
			echo "</br>";

			echo "<hr/></div>"; 

	 		echo"<footer style='color: white;' align='center'>2021 © Mathias Engambé</footer>";
?>

