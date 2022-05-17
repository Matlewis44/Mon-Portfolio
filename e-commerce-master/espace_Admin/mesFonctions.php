<?php
function logoAdmin(){
	echo"<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 10px; background-color: #000000;'>
		    <div id='brand'>
		       	<a href= '../espace_commun/accueilCommun.php?accueil=1' ><img src='../img/logo.png' alt='LOGO' title='Accueil' /></a>
		    </div>
	   	</div>
	    ";
}
function logoVisualisation(){
	echo"<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 10px; background-color: #000000;'>
		    <div id='brand'>
		       	<a href= '../espace_commun/accueilCommun.php?accueil=1' ><img src='../../img/logo.png' alt='LOGO' /></a>
		    </div>
	   	</div>
	    ";
}


function initMenuAdmin(){
	echo "
		<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 57px; background-color: #000000;'>
			<div id='navigation'>
				<ul>li><a href='../espace_commun/accueilCommun.php?accueil=1'>Accueil</a></li>
					<

					<li><a href='Visualisation/marque.php'>Marque</a></li>
		
					<li><a href='Visualisation/categorie.php'>Catégorie</a></li>
		
					<li><a href='Visualisation/sousCat.php'>Sous - Catégorie</a></li>
						
					<li><a href='Visualisation/Couleur.php'>Couleur</a></li>

					<li><a href='Visualisation/stock.php'>Stock</a></li>

					<li><a href='publier_Article.php'>Ajouter article</a></li>
				</ul>
			</div>
		</div>
	";
	echo "<div id=global>";
	echo "<h1 style='font-size:48px; text-align:center;'>Les articles</h1><hr/>";
}
function initMenu2Admin(){
	echo "
		<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 57px; background-color: #000000;'>
			<div id='navigation'>
				<ul>
					<li><a href='../espace_commun/accueilCommun.php?accueil=1'>Accueil</a></li>

					<li><a href='Visualisation/marque.php'>Marque</a></li>
		
					<li><a href='Visualisation/categorie.php'>Catégorie</a></li>
		
					<li><a href='Visualisation/sousCat.php'>Sous - Catégorie</a></li>

					<li><a href='Visualisation/Couleur.php'>Couleur</a></li>
						
					<li><a href='Visualisation/stock.php'>Stock</a></li>

					<li><a href='publier_Article.php'>Ajouter article</a></li>
				</ul>
			</div> 
		</div>
	";
}

function initBoutonAdmin(){
	echo "
		<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 57px; background-color: #000000; margin-bottom:20px;'>
			<div id='navigation'>
				<ul>
					<li><a href='../espace_commun/accueilCommun.php?accueil=1'>Accueil</a></li>

					<li><a href='marque.php'>Marque</a></li>
		
					<li><a href='categorie.php'>Catégorie</a></li>
		
					<li><a href='sousCat.php'>Sous - Catégorie</a></li>
						
					<li><a href='Couleur.php'>Couleur</a></li>

					<li><a href='stock.php'>Stock</a></li>

					<li><a href='../publier_Article.php'>Ajouter article</a></li>
				</ul>
			</div>
		</div>
	";
}


function initMenuAdminMembres(){
	echo "
		<link rel='stylesheet' href='stylesheet.css' />
		<div style='width: 100%; height: 57px; background-color: #000000;'>
			<div id='navigation'>
				<ul>
					<li><a href='VisualisationMembres/mail.php'>Mail</a></li>
		
					<li><a href='VisualisationMembres/adresse.php'>Adresse</a></li>
		
					<li><a href='VisualisationMembres/dateN.php'>Date de naissance</a></li>

					<li><a href='VisualisationMembres/nom.php'>Nom de famille</a></li>
						
					<li><a href='VisualisationMembres/pays.php'>Pays</a></li>

					<li><a href='VisualisationMembres/codePostale.php'>Code Postal</a></li>
					
					<li><a href='VisualisationMembres/ville.php'>Ville</a></li>

				</ul>
			</div> 
		</div>
	";
	
}
?>