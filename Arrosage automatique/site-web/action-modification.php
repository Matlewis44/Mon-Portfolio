<?php
//parametres de connexion
require("parametres.php");

//connexion au serveur MySQL
$connexion=mysqli_connect($serveur,$log,$pwd);

//Utiliser la bd souhaitée
$bd="projet-arrosage";
mysqli_select_db($connexion, $bd);

//Quelle est la table à consulter + Requête à faire
$table="utilisateurs";

session_start();
if (!isset($_SESSION['groupe']))
	header('Location: index.php?error=403');
else if (isset($_POST['newPassword'],$_POST['confirmPassword'])){
	
	if(isset($_SESSION['login'])){
		
		if (strcmp($_SESSION['groupe'],"usr")==0 and isset($_POST['password'])){
		
			$user = $_SESSION['login'];
			foreach($_POST as $k => $val){
				$$k=$val;
			}
		
			$requete="SELECT * FROM $table";
			$resultat=mysqli_query($connexion,$requete);
			
			$validation = false;
			
			while($ligne=mysqli_fetch_array($resultat)) {
				if (($user==$ligne[0]) && ($password==$ligne[1])) {
					$validation = true;
				}
			}
		}
		else if (strcmp($_SESSION['groupe'],"dev")==0){
			$validation = true;
			foreach($_POST as $k => $val){
				$$k=$val;
			}
			$user = $nomUtilisateur;
		}
		
		if($validation){
			
			if ($newPassword == $confirmPassword){
				if(strcmp($_SESSION['groupe'],"usr")==0 and strlen($newPassword) >= 4){
					
					$passwd = md5($newPassword);
					
					$requete="UPDATE `utilisateurs` SET `password`='$passwd' WHERE `login`='$user'";
					$resultat=mysqli_query($connexion,$requete);
					header('Location: profil.php?success=modified');
				}
				else if (strcmp($_SESSION['groupe'],"dev")==0){
					$passwd = md5($newPassword);
					
					$requete="UPDATE `utilisateurs` SET `password`='$passwd' WHERE `login`='$user'";
					$resultat=mysqli_query($connexion,$requete);
					
					header('Location: administration.php?success=modified');
				}
				else {
					if (strcmp($_SESSION['groupe'],"dev")==0)
						header('Location: administration.php?error=passwdlength');
					else
						header('Location: profil.php?error=passwdlength');
				}
			}
			else{
				if (strcmp($_SESSION['groupe'],"dev")==0)
					header('Location: administration.php?error=passwddifferents');
				else
					header('Location: profil.php?error=passwddifferents');
			}
			
			
		}
		else{
			header('Location: profil.php?error=wrongpasswd');
		}
		
		mysqli_close($connexion);
	}
	else{
		header('Location: index.php?error=401');
	}
}
else{
	header('Location: profil.php?error=incomplets');
}
?>