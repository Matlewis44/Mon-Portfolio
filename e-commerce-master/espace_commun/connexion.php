<?php
session_start();
?>


<?php
 /**
* Permet la connexion du client 
* Fait par mathias et krishnath
*/

function connexion()
{

	require('../src/log.php');
    echo ($_POST['email']);
    if(!empty($_POST['email']) && !empty($_POST['password'])){

        require('../src/connect.php');

        // VARIABLES
        $email 			= htmlspecialchars($_POST['email']);
        $password		= htmlspecialchars($_POST['password']);

        // ADRESSE EMAIL SYNTAXE
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            header('location: connexion.php?error=1&message=Votre adresse email est invalide.');
            exit();

        }

        // CHIFFRAGE DU MOT DE PASSE
        $password = "aq1".sha1($password."123")."25"; //fonction sha1 avec un grain de sel

        // EMAIL DEJA UTILISE
        $req = $db->prepare("SELECT count(*) as numberEmail FROM membres WHERE email = ?");
        $req->execute(array($email));

        while($email_verification = $req->fetch()){
            if($email_verification['numberEmail'] != 1){
                header('location: connexion.php?error=1&message=Cette adresse email existe pas, veuillez créer un compte.');
                exit();
            }
        }

        // CONNEXION
        $req = $db->prepare("SELECT * FROM membres WHERE email = ?");
        $req->execute(array($email));

        while($user = $req->fetch()){

            if($password == $user['password'] && $user['blocked'] ==0){

                $_SESSION['connect'] = 1;
                $_SESSION['user']   = $user['email'];
                $_SESSION['prenom']   = $user['prenom'];

                if(isset($_POST['auto'])){
                    setcookie('auth', $user['secret'], time() + 364*24*3600, '/', null, false, true);
                }

                header('location: ../espace_commun/accueilCommun.php?accueil=1');
                exit();

            }
            else {

                header('location: connexion.php?error=1&message=Impossible de vous authentifier correctement.');
                exit();

            }

        }

    }
}

?>

<?php
		/* FORMULAIRE DE CONNEXION */

require('../src/log.php');
if(!empty($_POST['email']) && !empty($_POST['password'])){
    require('../src/connect.php');
    connexion(); 

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Stunning outfit Shop</title>
	<!--meta name="viewport" content="width=device-width"-->
	<link rel="stylesheet" type="text/css" href="../design/formulaires.css">
	<!--link rel="stylesheet" type="text/css" href="../design/boutiqueFooter.css"-->

	<link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
</head>
<body>

<div id="brand" >
	<a href="accueilCommun.php?accueil=1" ><img style="height: 55px" src="../img/logo.png" alt=""></a>
</div>

	<section>
		<div id="login-body">
				
			<h1>Se connecter</h1>

				<?php if(isset($_GET['error'])) {

					if(isset($_GET['message'])) {
						echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
					}

				} ?>
				
			<form method="post" action="connexion.php">
				<input type="email" name="email" placeholder="Votre adresse email" required autofocus/>
				<input id = "pwd" type="password" name="password" placeholder="Mot de passe" required />
				<input type="checkbox" onclick = "Afficher()"> Afficher<br/><br/>

				<a href="recuperation.php" title="J'ai oublié mon mot de passe et je souhaite en mettre un nouveau">Mot de passe oublié</a></label>
				<br/><br/>
				<button type="submit">connexion</button>
				<label id="option"><input type="checkbox" name="auto" checked />Se souvenir de moi</label><br>
			</form>		

			<p class="grey">Pas encore membre? <a href="../espace_commun/inscription.php">Devenir membre</a></p>	<br>	
			<button style="width: 40%; padding: 5px; font-size: 0.85em" onclick="window.location.href='accueilCommun.php?accueil=1';">Revenir à l'accueil</button>
			<button style="width: 40%; margin-left:70px; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_Admin/index.php';">Espace administrateur</button>

					
		</div>
	</section>

	<?php include("../src/boutiqueFooter.php"); ?>
	<script src="../js/script.js"> </script>
</body>
</html>