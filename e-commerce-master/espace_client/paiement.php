<?php
session_start();
require('../src/connect.php');


if(!isset($_SESSION["user"])){ 
    {
    header('Location: ../espace_commun/connexion.php?error=1&message=Connectez-vous.');
    }
}


if(isset($_POST["payer"])){


    foreach ($_POST as $k => $v) $$k = $v;
    if(!empty($Nom) && !empty($Code) && !empty($Date_exp) && !empty($Crypto)) {

        if( strlen((string)$Code) !=16 ) {
            header('location: paiement.php?error=1&message=Code bancaire erroné.');
            exit();
        }

        if( strlen((string)$Crypto) !=3) {
            header('location: paiement.php?error=1&message=Crypto erroné.');
            exit();
        }

        header('location: commande.php');

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
        <!--link rel="stylesheet" type="text/css" href="../design/boutiqueFooter.css"-->

        <link rel="stylesheet" type="text/css" href="../design/footerFormulaires.css">
        <link rel="icon" type="image/pngn" href="../img/favicon.png">
    </head>
    <body>

        <div id="brand" >
            <a href="../espace_commun/accueilCommun.php?accueil=1" ><img style="height: 55px" src="../img/logo.png" alt=""></a>
        </div>
        <section>
            <div id="login-body">

                <h1>Paiement</h1>

                <?php if(isset($_GET['error'])){

                    if(isset($_GET['message'])) {

                        echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';

                    }

                }?>
                <div id="error"> </div>
                <form action="" method="post">
                    <label for="nom" class="form-label">Nom du titulaire</label>
                    <input type="text" name="Nom" placeholder="nom" required></input><br><br>

                    <label for="Numero de carte" class="form-label">Numéro de carte</label>
                    <input id="number" type="number" name="Code" placeholder="XXXXXXXXXX" required></input><br><br>

                    <label for="Date expiration" class="form-label">Date d'expiration</label>
                    <input type="date" name="Date_exp" placeholder="XX/XX" required></input><br><br>

                    <label for="Date expiration" class="form-label">Cryptograme</label>
                    <input id="crypto" type="number" name="Crypto" placeholder="XXX" required>
             

                    <input type="hidden" name="total" value="<?= $_SESSION['total'] ?>">
                    <button <input type="submit" name="payer" value="Finaliser le paiement"> Finaliser le paiement</button>
                </form>
            </div>
        </section>

        <?php include("../src/boutiqueFooter.php"); ?>
    </body>
</html>
