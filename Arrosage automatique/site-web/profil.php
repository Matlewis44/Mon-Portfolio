<?php
session_start();
require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"usr")==0){
		
		initMenu("<a href='accueil.php'>Accueil</a> > Profil");
		
		if(isset($_GET['error'])){
			$error = $_GET['error'];
			if ($error == "passwddifferents")
				$msg_error = "La confirmation du mot de passe est différentes du nouveau mot de passe saisi.";
			else if ($error == "passwdlength") 
				$msg_error = "Le mot de passe doit faire au moins 4 caractères.";
			else if ($error == "incomplets") 
				$msg_error = "Tous les champs doivent être remplis.";
			else if ($error == "wrongpasswd") 
				$msg_error = "Le mot de passe actuel entré est incorrect.";
			
			echo "<div style='padding: 2px;color: #871707; border: 1px solid #871707; background-color: #f6d9d8;
			margin-top: 10px; margin: 20px;
			padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_error."</div>"; 
		}
		if(isset($_GET['success'])){
			$success = $_GET['success'];
			if ($success == "modified")
				$msg_success = "Mot de passe modifié avec succès!";
			echo "<div style='padding: 2px;color: #0d3a78; border: 1px solid #0d3a78; background-color: #c2e0ed;
			margin-top: 10px; margin: 20px;
			padding: 10px;width: auto;text-align: center; box-sizing: border-box; border-radius: 3px;'>".$msg_success."</div>"; 
		}
		
		echo "<div id='configuration-systeme'>";
		
		echo"<h3><span id='texte-noir'>Modification du mot de passe</span></h3>";
		echo "
		<form action='action-modification.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Mot de passe actuel: </span></td> <td> <input type='password' name='password'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Nouveau mot de passe: </span></td> <td> <input type='password' name='newPassword'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Confirmer mot de passe: </span></td> <td> <input type='password' name='confirmPassword'></td>
			</tr>
		</table>
		<table>
			<tr>
				<td><input type='submit' name='ok' value='Valider' id='valider1'></td>
				<td><input type='reset' name='reset' value='Annuler' id='annuler1'></td>
			</tr>
		</table>
		</form>";
		
		echo"</div>";
		initFin();
	}
	else{
		header('Location: index.php?error=403');
	}
}
else
	header('Location: index.php?error=401');
	
?>