<?php

session_start();

$file="fichier_v0.csv";

//si l'utilisateur(admin) n'est pas déconnecté alors afficher une erreur 
if(isset($_SESSION['user'])){
	header('Location: formulaire.php?error=Déconnectez vous pour vous connecter');
}

//si login et mdp sont remplit
else if (isset($_POST['log'],$_POST['psw'])){
	
	foreach($_POST as $k => $val){
		$$k=$val;
    }
	
	//lire dans le csv
	if (file_exists($file)){
	
		$fp = fopen($file,'r');
		$connexion = false;
		while($resultat = fgetcsv($fp)){
			
			$password = md5($psw); /// hash du mot de passe pour le comparer au mot de passe hashé stocké dans le fichier csv
			
			if($log == $resultat[0] and $password == $resultat[1]){
				$connexion = true;
				$_SESSION["user"]=$log;
				$_SESSION["date"]=$time;
				if($log == "admin"){
					header('Location: admin.php');
					break;
				}
				else{
					header('Location: simulation.php');
					break;
				}
			}
			else if(feof($fp)){
				echo "fin de fichier";
				header('Location: formulaire.php?error=Combinaison login/password incorrecte');
			}
		}
		
		fclose($fp);
		if(!$connexion){
			header('Location: formulaire.php?error=Combinaison login/password incorrecte');
		}
	}
	else{
		echo "fichier existe pas";
		header('Location: formulaire.php?error=Fichier introuvable!');
	}
}
else{
	header('Location: formulaire.php?error=Impossible d\'accèder à cette page');
}
?>