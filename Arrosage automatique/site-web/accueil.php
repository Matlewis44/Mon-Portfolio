<html>
	<head>
		<meta charset="utf-8">
		<title>Arrosage automatique</title>
		<link rel="icon" type="image/ico" href="ressources/AWS.ico">
	</head>
</html>
<?php
session_start();

require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0 or strcmp($groupe,"usr")==0){
		initMenu("<a href='accueil.php'>Accueil</a>");

		echo"<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle.css' />";

		echo "<h2 style='padding-left: 30px; color:#404040;'>| Accueil |</h2>";
		echo "<div id='configuration-systeme'>";
		echo "<h3>Dernières données</h3>";

		require("parametres.php");

		// connexion au serveur MySQL
		$connexion=mysqli_connect($serveur,$log,$pwd);

		
		// Utiliser la bd souhaitée
		$bd="projet-arrosage";
		mysqli_select_db($connexion,$bd);

		// Quelle est la table à consulter + Requete à faire
		$table="data-collect";
	
		$requete="SELECT humidité FROM `dataCollect` order by date DESC ,heure DESC";
		$resultat=mysqli_query($connexion,$requete);
		
		$ligne=mysqli_fetch_array($resultat);
		mysqli_close($connexion);
		$humidité = $ligne[0];
		$temperature = 19;
		$consommation = 1.4;
		$quantité = 5;

		echo "<div class='container'> 
				<div class='card'>
					<div class='box'>
						<div class='pourcent'>
							<svg>
								<circle cx='70' cy='70' r='70'></circle>
								<circle cx='70' cy='70' r='70'></circle>
							</svg>
							<div class='nombre'>
								<h2>$humidité<span>%</span></h2>
								<style> .card:nth-child(1) svg circle:nth-child(2){stroke-dashoffset: calc(440 - (440 * $humidité) / 100);} </style>
							</div>
						</div>
						<h2 class='text'> Taux d'humidité </h2>
					</div>
				</div>
				<div class='card'>
					<div class='box'>
						<div class='pourcent'>
							<svg>
								<circle cx='70' cy='70' r='70'></circle>
								<circle cx='70' cy='70' r='70'></circle>
							</svg>
							<div class='nombre'>
								<h2>$temperature<span>°C</span></h2>
								<style> .card:nth-child(2) svg circle:nth-child(2){stroke-dashoffset: calc(440 - (440 * $temperature) / 30);} </style>
							</div>
						</div>
						<h2 class='text'> Température </h2>
					</div>
				</div>
				<div class='card'>
					<div class='box'>
						<div class='pourcent'>
							<svg>
								<circle cx='70' cy='70' r='70'></circle>
								<circle cx='70' cy='70' r='70'></circle>
							</svg>
							<div class='nombre'>
								<h2>$consommation<span>L</span></h2>
								<style> .card:nth-child(3) svg circle:nth-child(2){stroke-dashoffset: calc(440 - (440 * $consommation) / 10);} </style>
							</div>
						</div>
						<h2 class='text'> Consommation d'eau </h2>
					</div>
				</div>
				<div class='card'>
					<div class='box'>
						<div class='pourcent'>
							<svg>
								<circle cx='70' cy='70' r='70'></circle>
								<circle cx='70' cy='70' r='70'></circle>
							</svg>
							<div class='nombre'>
								<h2>$quantité<span>L</span></h2>
								<style> .card:nth-child(4) svg circle:nth-child(2){stroke-dashoffset: calc(440 - (440 * $quantité) / 10);} </style>
							</div>
						</div>
						<h2 class='text'> Quantité d'eau dans le réservoir </h2>
					</div>
				</div>																					
			</div>";
		echo "</div>";
		echo "<div class='espace'></div>
		<div id='configuration-systeme'>
			<div class='custom'>
				<h2>Créez vos configurations personnalisées maintenant !</h2> <br />
				<a href='interface.php'><input id='valider1' type='submit' name='ok' value='Personnalisation' id='button'></a>
			</div>
			";

		
		echo "</div>";
		initFin();
	}
	else
		header('Location: index.php?error=403');
}
else
	header('Location: index.php?error=401');
	
?>
