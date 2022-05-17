<?php
function display($a){
echo "<pre>";
print_r($a);
echo "</pre>";
}

function initMenu($chemin){
echo "
<head>
<meta charset='UTF-8'>
<link rel='icon' href='ressources/AWS.ico' />
<title>AWS PROJECT</title>

<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle3.css' />
<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle4.css' />

<script src='https://cdn.jsdelivr.net/npm/chart.js@2.8.0'></script>

	<div id='barre'>
		<a href=index.php style='text-decoration: none;'><span style='color: #30a5ff'>AWS</span> <span style='color: #FFFFFF'>PROJECT</span></a>
	</div>
	<script language='javascript'>
			function verif(i)
			{
				var numero = i;
				var nomForm = 'supprimerUtilisateur' + numero;
				var nomUtilisateur = document.forms[nomForm]['nomUtilisateur'].value;
				return confirm('Voulez-vous supprimer l\'utilisateur ' + nomUtilisateur + ' ?');
			}
	</script>
	
</head>
<body>
<div id='global'>

	<div id='menu' style='float: left;'>
		<div id='statut'>";
			if(isset($_SESSION['groupe'])){
				$login = $_SESSION['login'];
				$login[0] = strtoupper($login[0]);
				echo "
					<table>
						<tr>
						<td> 
							<img src='ressources/icon_profil.png' height='50px' width='50px'>
						</td>
						<td>
							<table>
								<tr><span id='texte-noir'><p style='font-size: 20px; text-align: left; padding-left: 3px;'>".$login."</p></span></tr>
								<tr><td><div id='cercle' style='background: #8ad919;'></div></td><td><span id='texte-noir'><b style='font-size: 10px;'>EN LIGNE</b></span></td></tr>
							</table>
						</td>
						</tr>
					</table>
				";
			}
			else{
				echo"<table>
						<tr>
							<td><div id='cercle' style='background: #cc0000;'></div></td>
							<td><span id='texte-noir'><p style='font-size: 15px;'>DÉCONNECTÉ</b></span></td>
						</tr>
					</table>
					";
			}
		echo"</div><hr />
		<div id='barre-menu'>
            <ul id='menu-vertical'>
                <li><a href='index.php'><img id='dashboard' src='ressources/icon_dashboard.png' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Accueil</a></li>
                <li><a href='interface.php'><img src='ressources/icon_wrench.png' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Configuration système</a></li>
                <li><a href='analytique.php'><img src='ressources/icon_bar_chart.jpg' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Analytique</a></li>";
                
                if (isset($_SESSION['groupe']) and $_SESSION['groupe'] == "dev"){
                    echo "<li><a href='administration.php'><img src='ressources/icon_adress_book1.png' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Administration</a></li>";
                }
                else{
                    echo "<li><a href='profil.php'><img src='ressources/icon_adress_book1.png' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Profil</a></li>";
                }
            
            echo "</ul>
            <ul id='bouton-deco'>
                <li><a href='action-deconnexion.php?dcx=Déconnexion'><img src='ressources/icon_power_off.png' height='20px' width='20px' style='vertical-align: middle; padding-right: 5px;'>Déconnexion</a></li>
            </ul>
        </div>
	</div><!-- #menu -->
	
	<div id='page' style='float: right;'>
		<div id='path'>";
			echo $chemin;
		echo" </div>
	
		<div id='contenu'>";
}


function initFin(){
	echo "
			<div id='pied'>
				<p style='text-align: center; font-size: 10px;'>AWS PROJECT</p>
			</div><!-- #pied -->
		</div><!-- #contenu (je crois) -->
		</div><!-- #page -->
		</div><!-- #global -->
	</body>";
}

function getLabels(){
	$i = 0;
	while($i < 48){
		$heure = date("H\h00",mktime(date("H")-$i, 0, 0, date("m"), date("d"), date("Y")));
		if($heure == 0){
			$graduation[$i] = date("d/m/Y",mktime(date("H")-$i, 0, 0, date("m"), date("d"), date("Y")));
		}
		else{
			$graduation[$i]= $heure;
		}
		$i += 1;
	}
	
	return $graduation;
}

function dbToArray(){
	
	$i = 0;
	while($i < 48){
	  $graduation[$i] = date("H\h00 d/m/Y",mktime(date("H")-$i, 0, 0, date("m"), date("d"), date("Y")));
	  $i += 1;
	}
	
	require("parametres.php");

	// connexion au serveur MySQL
	$connexion=mysqli_connect($serveur,$log,$pwd);


	// Utiliser la bd souhaitée
	$bd="projet-arrosage";
	mysqli_select_db($connexion,$bd);

	// Quelle est la table à consulter + Requete à faire
	$table="dataCollect";
	
	$requete="SELECT DATE_FORMAT(date, '%d/%m/%Y'),heure,humidité FROM `dataCollect` order by date DESC ,heure DESC";
	$resultat=mysqli_query($connexion,$requete);
	
	$array= array();
	
	for($i = 0; $i < 48; $i++){
        $array[$i] = 'NaN';
    }
        
    while($ligne=mysqli_fetch_array($resultat)) {
		$datebrut = $ligne[0];
		if(strlen($ligne[1]) == 1){
			$ligne[1] = "0".$ligne[1];
		}
		$date = $ligne[1]."h00 ".$datebrut;
		$i = 0;
		$okVal = 0; //indique si la date est dans graduation
		while($okVal != 1 && $i < 48){
			if($date == $graduation[$i]){
				$array[$i] = $ligne[2]; //humidité
				$okVal = 1;
			}
			$i += 1;
		}
    }
	
	mysqli_close($connexion);
	return $array;
}

function getDates(){
    $i = 0;
    while($i < 48){
        $graduation[$i] = date("d/m/Y",mktime(date("H")-$i, 0, 0, date("m"), date("d"), date("Y")));
        $i += 1;
    }
    
    return $graduation;
}
?>
