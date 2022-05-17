<?php
session_start();
require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0){
		
		initMenu("<a href='accueil.php'>Accueil</a> > Modification mot de passe");
	
		foreach($_POST as $k => $v){
			$$k= $v;
		}
		$utilisateurCible = $nomUtilisateur;

		echo "<div id='configuration-systeme'>";
		
		echo"<h3><span id='texte-noir'>Modification du mot de passe de l'utilisateur $utilisateurCible</span></h3>";
		echo "
		<form action='action-modification.php' method='post'>
		<table cellpadding='4'>
			<tr>
				<td><span id='texte-noir'> Nouveau mot de passe: </span></td> <td> <input type='password' name='newPassword'></td>
			</tr>
			<tr>
				<td><span id='texte-noir'> Confirmer mot de passe: </span></td> <td> <input type='password' name='confirmPassword'></td>
			</tr>
		</table> 
		<table>
			<tr>
				<input type='hidden' name='nomUtilisateur' value='$utilisateurCible'>
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