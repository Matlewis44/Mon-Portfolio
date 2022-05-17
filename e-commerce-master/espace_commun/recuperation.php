<?php 
//////////////              PARTIE BACK-END                //////////////

session_start();

require('../src/connect.php');

if(isset($_GET['section'])) {
    $section=htmlspecialchars($_GET['section']);
}else{
 
$section="";}


 if(isset($_POST['recup_submit'],$_POST['recup_mail'])) {
    if(!empty($_POST['recup_mail'])) {
       $recup_mail = htmlspecialchars($_POST['recup_mail']);

       if(filter_var($recup_mail,FILTER_VALIDATE_EMAIL)) {
          $mailexist = $db->prepare('SELECT id FROM membres WHERE email = ?');
          $mailexist->execute(array($recup_mail));
          $mailexist_count = $mailexist->rowCount();

          if($mailexist_count == 1) {
             /*$pseudo = $mailexist->fetch();
             $pseudo = $pseudo['pseudo'];*/
             
             $_SESSION['recup_mail'] = $recup_mail;
             $recup_code = "";
             for($i=0; $i < 8; $i++) { 
                $recup_code .= mt_rand(0,9);
             }

             // On cherche à voir si l'adresse mail n'est pas déjà dans la table
             $mail_recup_exist = $db->prepare('SELECT id FROM recuperation WHERE mail = ?');
             $mail_recup_exist->execute(array($recup_mail));
             $mail_recup_exist = $mail_recup_exist->rowCount();

             //Si le mail existe déjà dans la table on met le code à jour, sinon on insère une nouvelle entrée
             if($mail_recup_exist == 1) {
                $recup_insert = $db->prepare('UPDATE recuperation SET code = ? WHERE mail = ?');
                $recup_insert->execute(array($recup_code,$recup_mail));
             } else {
                $recup_insert = $db->prepare('INSERT INTO recuperation(mail,code) VALUES (?, ?)');
                $recup_insert->execute(array($recup_mail,$recup_code));
             }
             $header="MIME-Version: 1.0\r\n";
          $header.='From: "maengambe@gmail.com"<maengambe@gmail.com>'."\n";
          $header.='Content-Type:text/html; charset="utf-8"'."\n";
          $header.='Content-Transfer-Encoding: 8bit';
          /////     LE MAIL   ///////
          $message = '
          <html>
          <head>
            <title>Récupération de mot de passe - Stunning outfit shop</title>
            <meta charset="utf-8" />
          </head>
          <body>
            <font color="#303030";>
              <div align="center">
                <table width="600px">
                  <tr>
                    <td>
                      
                      <div align="center">Vous revoilà ! </b>,</div>
                      Voici votre code de récupération: <b>'.$recup_code.'</b></br>
                      A bientôt sur <a href="#">Votre site</a> !
                      
                    </td>
                  </tr>
                  <tr>
                    <td align="center">
                      <font size="2">
                        Ceci est un email automatique, merci de ne pas y répondre
                      </font>
                    </td>
                  </tr>
                </table>
              </div>
            </font>
          </body>
          </html>
          ';
          mail($recup_mail, "Récupération de mot de passe - Votresite", $message, $header);
             header("Location: recuperation.php?section=code");
             /*"Location:http://127.0.0.1/path/recuperation.php?section=code"*/
          } else {
             $error = "Cette adresse mail n'est pas enregistrée";
          }
       } else {
          $error = "Adresse mail invalide";
       }
    } else {
       $error = "Veuillez entrer votre adresse mail";
    }
 }
 if(isset($_POST['verif_submit'],$_POST['verif_code'])) {
      if(!empty($_POST['verif_code'])) {
         $verif_code = htmlspecialchars($_POST['verif_code']);
         $verif_req = $db->prepare('SELECT id FROM recuperation WHERE mail = ? AND code = ?');
         $verif_req->execute(array($_SESSION['recup_mail'],$verif_code));
         $verif_req = $verif_req->rowCount();
         if($verif_req == 1) {
            $up_req = $db->prepare('UPDATE recuperation SET confirme = 1 WHERE mail = ?');
            $up_req->execute(array($_SESSION['recup_mail']));
            header('Location: recuperation.php?section=changemdp');
       } else {
          $error = "Code invalide";
       }
    } else {
       $error = "Veuillez entrer votre code de confirmation";
    }
 }

 if(isset($_POST['change_submit'])) {
    if(isset($_POST['change_mdp'],$_POST['change_mdpc'])) {
       $verif_confirme = $db->prepare('SELECT confirme FROM recuperation WHERE mail = ?');
       $verif_confirme->execute(array($_SESSION['recup_mail']));
       
       $verif_confirme = $verif_confirme->fetch();
       $verif_confirme = $verif_confirme['confirme'];
       if($verif_confirme == 1) {
          $mdp = htmlspecialchars($_POST['change_mdp']);
          $mdpc = htmlspecialchars($_POST['change_mdpc']);
          
          if(!empty($mdp) AND !empty($mdpc)) {
             if($mdp == $mdpc) {
                $mdp = "aq1".sha1($mdp."123")."25";
                $ins_mdp = $db->prepare('UPDATE membres SET password = ? WHERE email = ?');
                $ins_mdp->execute(array($mdp,$_SESSION['recup_mail']));

               $del_req = $db->prepare('DELETE FROM recuperation WHERE mail = ?');
               $del_req->execute(array($_SESSION['recup_mail']));
                header('Location: connexion.php');
             } else {
                $error = "Vos mots de passes ne correspondent pas";
             }
          } else {
             $error = "Veuillez remplir tous les champs";
          }
       } else {
          $error = "Veuillez valider votre mail grâce au code de vérification qui vous a été envoyé par mail";
       }
    } else {
       $error = "Veuillez remplir tous les champs";
    }
 }
 ?>
                                <!-- PARTIE FRONT-END -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href = "../design/formulaires.css">
	<link rel="icon" type="image/pngn" href="../img/favicon.png">
    <title>Stunning outfit Shop</title>
</head>
<body>
   <header>
      <div id="brand">
         <a href="accueilCommun.php" ><img src="../img/logo.png" alt="LOGO" /></a>
      </div>
   </header>

	<section>
		<div id="login-body">
            <h4 class="title-element">Récupération de mot de passe</h4>
            <?php if($section == 'code') { ?>
            Un code de vérification vous a été envoyé par mail: <?= $_SESSION['recup_mail'] ?>
            <br/>
            <form method="post">
            <input type="text" placeholder="Code de vérification" name="verif_code"/><br/>
            <input type="submit" value="Valider" name="verif_submit"/>
            </form>
            <?php } else if($section == "changemdp") { ?>
               
            Nouveau mot de passe pour <?= $_SESSION['recup_mail'] ?>
            <form method="post">
               <input id = "pwd" type="password" placeholder="Nouveau mot de passe" name="change_mdp"/><br/>
               <input type="checkbox" onclick = "Afficher()"> Afficher <br/><br/>

               <p>Votre mot de passe doit contenir au moins: <br/>
					<span>une minuscule</span> /
					<span >une majuscule</span> /
					<span >un chiffre</span> /
					<span >un caractère spécial ($@!%*#/\&).</span> 
					<span >Et faire au moins 8 caractères</span> 
				</p>
            
               <input id = "pwd2" type="password" placeholder="Confirmation du mot de passe" name="change_mdpc"/><br/>
               <input type="checkbox" onclick = "Afficher2()"> Afficher <br/><br/>
               <input type="submit" value="Valider" name="change_submit"/>
            </form>

            <?php } else { ?>
            <form method="post">
               <input type="email" placeholder="Votre adresse mail" name="recup_mail"required/><br/>
               <input type="submit" value="Valider" name="recup_submit"/>
            </form>
            <?php } ?>
            <?php if(isset($error)) { echo '<span style="color:red">'.$error.'</span>'; } else { echo ""; } ?>
        </div>
    
    </section>

    <script src ="../js/script.js"> </script>
    </body>
</html>