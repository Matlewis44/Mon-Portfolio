<?php
/* PROFIL */
require('../src/log.php');
require('../src/connect.php');
session_start();

 /* DOIT ETRE CONNECTE SINON REDIRIGER */
 if(!isset($_SESSION["user"])){
    header('Location: ../espace_commun/connexion.php?error=1&message=Connectez-vous.');
    exit();
}


if(isset($_SESSION['user'])) {

    foreach ($_POST as $k => $v) $$k = $v;
    $requser = $db->prepare("SELECT * FROM membres WHERE email = ?");
    $requser->execute(array($_SESSION['user']));
    $user = $requser->fetch();

    if(isset($_POST["ok"])){

        if(isset($newcivilite) AND !empty($newcivilite) AND $newcivilite != $user['civilité']) {
            $insertcivilite = $db->prepare("UPDATE membres SET civilite = ? WHERE email = ?");
            $insertcivilite->execute(array($newcivilite, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newprenom) AND !empty($newprenom) AND $newprenom != $user['prenom']) {
            $newprenom = htmlspecialchars($newprenom);
            $insertprenom = $db->prepare("UPDATE membres SET prenom = ? WHERE email = ?");
            $insertprenom->execute(array($newprenom, $_SESSION['user']));
            $_SESSION['prenom'] = $newprenom;  
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newnom) AND !empty($newnom) AND $newnom != $user['nom']) {
            $newnom = htmlspecialchars($newnom);
            $insertnom = $db->prepare("UPDATE membres SET nom = ? WHERE email = ?");
            $insertnom->execute(array($newnom, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newbirthDate) AND !empty($newbirthDate) AND $newbirthDate != $user['date_naissance']) {
            $insertdate = $db->prepare("UPDATE membres SET date_naissance = ? WHERE email = ?");
            $insertdate->execute(array($newbirthDate, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newadresse1) AND !empty($newadresse1) AND $newadresse1 != $user['adresse']) {
            $insertadresse = $db->prepare("UPDATE membres SET adresse = ? WHERE email = ?");
            $insertadresse->execute(array($newadresse1, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newadresse2) AND !empty($newadresse2) AND $newadresse2 != $user['adresse2']) {
            $insertadresse2 = $db->prepare("UPDATE membres SET adresse2 = ? WHERE email = ?");
            $insertadresse2->execute(array($newadresse2, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newadresseLivraison) AND !empty($newadresseLivraison) AND $newadresseLivraison != $user['adresse_livraison']) {
            $insertadresselivraison = $db->prepare("UPDATE membres SET adresse_livraison = ? WHERE email = ?");
            $insertadresselivraison->execute(array($newadresseLivraison, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        } 

        if(isset($newnumero) AND !empty($newnumero) AND $newnumero != $user['Tél']) {
            $insertnum = $db->prepare("UPDATE membres SET Tél = ? WHERE email = ?");
            $insertnum->execute(array($newnumero, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }
        if(isset($newpays) AND !empty($newpays) AND $newpays != $user['pays']) {
            $insertpays = $db->prepare("UPDATE membres SET pays = ? WHERE email = ?");
            $insertpays -> execute(array($newpays, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newville) AND !empty($newville) AND $newville != $user['ville']) {
            $insertville = $db->prepare("UPDATE membres SET ville = ? WHERE email = ?");
            $insertville -> execute(array($newville, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newcodepostal) AND !empty($newcodepostal) AND $newcodepostal != $user['code_postal']) {
            $insertcodepostal = $db->prepare("UPDATE membres SET code_postal = ? WHERE email = ?");
            $insertcodepostal -> execute(array($newcodepostal, $_SESSION['user']));
            header('Location: profil.php?success=1');exit();
        }

        if(isset($newemail) AND !empty($newemail) AND $newemail != $user['email']) {

            checkMail($newemail);
            $req = $db->prepare("SELECT count(*) as numberEmail FROM membres WHERE email = ?");
            $req->execute(array($email));

            while($email_verification = $req->fetch()){

                if($email_verification['numberEmail'] != 0){

                    header('location: profil.php?error=1&message=Cette adresse email est déjà utilisée par un autre utilisateur.');
                    exit();

                }

            }
            $insertemail = $db->prepare("UPDATE membres SET email = ? WHERE email = ?");
            $insertemail -> execute(array($newemail, $_SESSION['user']));
            $_SESSION['user'] = $newemail;  
            header('Location: profil.php?success=1');exit();
        }

        if(!empty($oldmdp1) AND !empty($oldmdp2) AND !empty($newmdp)) {

            // CHIFFRAGE DU MOT DE PASSE
            $oldmdp1 = "aq1".sha1($oldmdp1."123")."25"; 
            $oldmdp2 = "aq1".sha1($oldmdp2."123")."25"; 
            
            if ($oldmdp1 == $user['password']){
                // PASSWORD = PASSWORD TWO
                if($oldmdp1 == $oldmdp2){

                    checkPassword($newmdp);

                    // CHIFFRAGE DU MOT DE PASSE
                    $newmdp = "aq1".sha1($newmdp."123")."25";
    
                    $insertmdp = $db->prepare("UPDATE membres SET password = ? WHERE email = ?");
                    $insertmdp -> execute(array($newmdp, $_SESSION['user']));
                    header('Location: profil.php?success=1');exit();

                }else {
                    header('location: profil.php?error=1&message=Les mot de passes ne sont pas identiques.');exit();
                }
            

            }else {
                header('location: profil.php?error=1&message=Mot de passe incorrecte.');exit();
            }



        }else {
            header('location: profil.php?error=1&message=Rentrez tout les champs de mot de passe.');exit();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Stunning outfit Shop</title>
	<!--meta name="viewport" content="width=device-width"-->
	<!--link rel="stylesheet" type="text/css" href="../design/boutiqueFooter.css"-->

	<link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
    <link rel="stylesheet" type="text/css" href="../design/formulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>


<header>
      	<div id="brand">
         	<a href= "../espace_commun/accueilCommun.php?accueil=1" ><img src="../img/logo.png" alt="LOGO" /></a>
      	</div>
   	</header>
	
	<section>
		<div id="login-body">
				
			<h1>Votre profil actuel</h1>

            <?php if(isset($_GET['error'])){

                if(isset($_GET['message'])) {

                    echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

                }

            } else if(isset($_GET['success'])) {

            echo'<div class="alert success">Vous avez modifié votre profil. <a href="../espace_commun/accueilCommun.php?accueil=1"><b>Revenir à l\'accueil</b></a>.</div>';

            } ?>
				
			<form method="post" action="profil.php">
                <!--Civilité-->
			    <div class="input"><br>
					<label for="civilité" class="form-label">Civilité</label>
					<select name="civilite" class="form-select" id="civilite" required>
						<option><?php echo $user['civilité']; ?></option>
						<option>Mr</option>
						<option>Mme</option>
					</select>
				</div>
				<!--Prénom-->
				<div class="input"><br>
					<label for="lastName" class="form-label">Prénom</label>
              		<input name="newprenom" type="text" placeholder="Prénom" value="<?php echo $user['prenom']; ?>" /><br /><br />
				</div>
				<!--Nom-->
				<div class="input">
					<label for="lastName" class="form-label">Nom de famille</label>
              		<input name="newnom" type="text" id="lastName" placeholder="Nom de famille" value="<?php echo $user['nom']; ?>" /><br /><br />
				</div>
				<!--Date Naissance-->
				<div class="input">
					<label for="date" class="form-label">Date de naissance </label>
              		<input name="newbirthDate" type="date" placeholder="Date de naissance" value="<?php echo $user['date_naissance']; ?>" /><br /><br />
			  	</div><br>

				<!--Adresse Principale-->
				<div class="input">
					<label for="address" class="form-label">Adresse</label>
					<input name="newadresse1" type="text" id="address" placeholder="1234 Main St" value="<?php echo $user['adresse']; ?>" /><br /><br />
            	</div>
				
				<!--Deuxième adresse-->
				<div class="input">
					<label >Complément adresse <span style="font-size: 0.8em;">(Optionel)</span></label>
					<input name="newadresse2" type="text" id="address" placeholder="Vide" value="<?php echo $user['adresse2']; ?>" /><br /><br />	
            	</div>

				<!--Adresse Livraison-->
				<div class="input">
					<label for="address" class="form-label">Adresse de livraison</label>
					<input name="newadresseLivraison" type="text" id="address" placeholder="1234 Main St" value="<?php echo $user['adresse_livraison']; ?>" /><br /><br />	
            	</div>

				<!--Number-->
				<div class = "input">
					<label for="zip" class="form-label">Numéro</label>
					<input type="text" name="newnumero" placeholder="Votre numéro" value="<?php echo $user['Tél']; ?>" /><br /><br />
				</div>

				<!--Pays-->
				<div class="input"><br>
					<label for="country" class="form-label">Pays</label>
              		<input name="newpays" type="text" placeholder="Pays" value="<?php echo $user['pays']; ?>" /><br /><br />
				</div>
		

				<!--Ville-->
				<div class="input"><br>
					<label for="state" class="form-label">Ville</label>
              		<input name="newville" type="text" placeholder="state" value="<?php echo $user['ville']; ?>" /><br /><br />
				</div>

				<!--Code postal -->
				<div class="input">
					<label for="zip" class="form-label">Code postal</label>
					<input name="newcodepostal" type="text" class="form-control" id="zip" placeholder="92500" value="<?php echo $user['code_postal']; ?>" /><br /><br />
				</div>

				<!--Email-->
				<div class = "input">
					<label for="zip" class="form-label">Email</label>
					<input type="text" name="newemail" placeholder="Votre adresse email" value="<?php echo $user['email']; ?>" /><br /><br />
				</div>

				<!--Mot de passe-->
				<div class = "input">
					<i>
						<p>Votre mot de passe doit contenir au moins: <br/>
							<span id="minuscule" class="invalid">une minuscule</span> /
							<span id="majuscule" class="invalid">une majuscule</span> /
							<span id="chiffre" class="invalid">un chiffre</span> /
							<span id="special" class="invalid">un caractère spécial ($@!%*#/\&).</span> 
							<span id="longueur" class="invalid">Et faire au moins 8 caractères</span> 
						</p>
					</i>
                    <br/><br/>
					<label for="zip" class="form-label">Mot de passe</label>
					<input type="password" name="oldmdp1" id = "pwd" placeholder="Mot de passe"  />
					<input type="checkbox" onclick = "Afficher()"> Afficher
					<br/><br/>
					<!--img src="img/close_eye.png" id = "eye" onClick="changer()" /-->
				</div>
			
				<!--Confirmation mot de passe-->
                <div class = "input">
                    <input type="password" name="oldmdp2" id = "pwd2" placeholder="Retapez votre mot de passe"  />
                    <input type="checkbox" onclick = "Afficher2()"> Afficher <br/><br/>
                </div>

                <!--Confirmation mot de passe-->
                <div class = "input">
                    <input type="password" name="newmdp" id = "newpwd" placeholder="Nouveau mot de passe"  />
                    <input type="checkbox" onclick = "Afficher2()"> Afficher <br/><br/>
                </div>


				<button type="submit" name = "ok" class>Modifier son profil</button>
			</form>		    

			<button style="width: 40%; margin-top: 30px; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_commun/accueilCommun.php';">Revenir à l'accueil</button>


					
		</div>
	</section>

	<?php include("../src/boutiqueFooter.php"); ?>
	<script src="../js/script.js"> </script>
</body>
</html>


<?php
// PASSWORD_TWO VERIFICATION STRONG
function checkPassword($pwd) {
    if( strlen($pwd) < 8 ) {
        header('location: profil.php?error=1&message=Mot de passe trop court.');
        exit();
    }

    if( !preg_match("#[0-9]+#", $pwd) ) {
        header('location: profil.php?error=1&message=Mot de passe doit inclure au moins 1 chiffre.');
        exit();
    }

    if( !preg_match("#[a-z]+#", $pwd) ) {
        header('location: profil.php?error=1&message=Mot de passe doit inclure au moins 1 minuscule.');
        exit();
    }

    if( !preg_match("#[A-Z]+#", $pwd) ) {
        header('location: profil.php?error=1&message=Mot de passe doit inclure au moins 1 majuscule.');
        exit();
    }
        

    if( !preg_match("#\W+#", $pwd) ) {
        header('location: profil.php?error=1&message=Mot de passe doit inclure au moins 1 caractère spécial.');
        exit();
    }
        
    
}


function checkMail($email) {

    // ADRESSE EMAIL VALIDE
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        header('location: profil.php?error=1&message=Votre adresse email est invalide.');
        exit();

    }

}

?>