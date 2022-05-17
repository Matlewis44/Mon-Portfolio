<html>
	
	<body>
		<?php 

		$file="fichier.csv";

		session_start();

		if(isset($_SESSION['user'])){
			if($_SESSION['user'] == "admin"){
				
				require_once("mesFonctions.php");
				
				initMenu();
			
				$user = $_SESSION["user"];
			
				initStatut($user);
			
				if(isset($_GET['success'])) {
					$code = $_GET['success'];
					echo "<p style='color: #0000ff;'> $code </p>";
				}
				if(isset($_GET['error'])) {
					$erreur = $_GET['error'];
					echo "<p style='color: #ff0000;'> $erreur </p>";
				}
			
				echo"<table id='cadre' cellpadding=5 cellspacing=3 style='width: 1000px;margin-left:auto;margin-right:auto;'>";
				if (file_exists($file)){
					
					$fp = fopen($file,'r');
					$resultat = fgetcsv($fp);
					
					echo"<tr>";
					for($i=0;$i<6;$i++){
						echo "<th>".$resultat[$i]."</th>";
					}

					echo"</tr>";
					
					$j = 1;
					while($resultat = fgetcsv($fp)){
						echo"<tr>";
						for($i=0;$i<6;$i++){
							if($i == 0)
								echo "<td><p style='font-size: 14px;'>".$resultat[$i]."</p></td>";
							else
								echo "<td><p style='font-size: 10px;'>".$resultat[$i]."</p></td>";
						}



						echo"</tr>";
						$j++;
					}
					fclose($fp);
				}
				echo"</table>";
				echo "
				<form action='' method='POST'>
				<input id='bouton' type='SUBMIT' name='clear' value='Effacer' style='margin-top:1em'>
				<input id='bouton' type='SUBMIT' name='archive' value='Archiver' style='margin-top:1em'>
				</form>
				";

				if (isset($_POST['clear'])){
					$entete = array("Capital","Taux d'intéret","Nombre de mois","Mensualité","date","adresse");
					
					$csv = new SplFileObject('fichier.csv', 'w');
					$csv->fputcsv($entete, ',');
					header('Location: admin.php');
				}

				if (isset($_POST['archive'])){
					archivage("fichier.csv");
	
					header('Location: admin.php');
				}
			
			
			}
			
			else{
				header('Location: formulaire.php?error=Vous ne disposez pas des permissions neccesaires');
				
			}
			echo "</div>";
			echo "<div id='bas'/></body>";
		}
		else{
			header('Location: formulaire.php?error=Vous n\'êtes pas connecté');
		}

		?>
	</body>
	
</html>