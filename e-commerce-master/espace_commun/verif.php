<?php
/* FORMULAIRE D'INSCRIPTION */
session_start();

require('../src/log.php');


if(isset($_POST["ok"])){



    if ($_SESSION["cle"] == $_POST['cle']){

        foreach($_SESSION["info"] as $keys => $values)
        require('../src/connect.php');
        {
            $req = $db->prepare("INSERT INTO membres(id_client,email, password, secret, adresse, adresse_livraison, date_naissance, prenom, nom, pays, code_postal, ville, tél, civilité) VALUES(?,?,?,?, ?,?,?, ?,?,?, ?,?,?,?)");
	        $req->execute(array($_SESSION["info"][0]["idclient"],$_SESSION["info"][0]["email"], $_SESSION["info"][0]["password"], $_SESSION["info"][0]["secret"], $_SESSION["info"][0]["adresse1"], $_SESSION["info"][0]["adresseLivraison"], $_SESSION["info"][0]["birthDate"], $_SESSION["info"][0]["prenom"], $_SESSION["info"][0]["nom"], $_SESSION["info"][0]["pays"], $_SESSION["info"][0]["codePostal"], $_SESSION["info"][0]["ville"], $_SESSION["info"][0]["numero"], $_SESSION["info"][0]["civilite"]));

        }

        unset($_SESSION["info"]);
        header('location: inscription.php?success=1');
	    exit();


    }

    else {
        header('location: verif.php?error=1&message=La clé est fausse.');
    }

}



    



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Stunning outfit Shop</title>
	<!--meta name="viewport" content="width=device-width"-->
	<link rel="stylesheet" type="text/css" href="../design/formulaires.css">
	<link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>

	<header>
      	<div id="brand">
         	<a href= "accueilCommun.php?accueil=1" ><img src="../img/logo.png" alt="LOGO" /></a>
      	</div>
   	</header>
	
	<section>
		<div id="login-body">
			<h1>Verification</h1>

            
			<?php if(isset($_GET['error'])){

				if(isset($_GET['message'])) {

					echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

				}

			}  ?>

			<form method="post" action="verif.php" >


				<!--code verif -->
				<div class = "input">
					<label for="zip" class="form-label">Rentrez votre clé</label>
					<input type="text" name="cle" placeholder="Votre clé" required />
				</div>

				<button type="submit" name = "ok" class>Valider</button>
			</form>

		</div>
	</section>

	<?php include('../src/boutiqueFooter.php'); ?>
	<script src ="../js/script.js"> </script>
</body>
</html>