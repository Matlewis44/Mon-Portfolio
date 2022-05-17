<?php
session_start();
require('../src/connect.php');

if(isset($_SESSION["user"])){ 
    
    if(isset($_POST["add_to_cart"]))
    {
        if (isset($_POST['taille'])){
            $id = $_POST['hidden_id'];
            $recupDispo = $db->query("SELECT dispo from taille where id_ref = '$id'");
            $nbrDispo = $recupDispo->fetchColumn();
            
            $nbrDispoApres = $nbrDispo - $_POST["quantity"];
            
            if ($nbrDispoApres>0){
                
                if(isset($_SESSION["shopping_cart"]))
                {
                    $item_array_id = array_column($_SESSION["shopping_cart"], "item_ref");
                    if(!in_array($_GET["reference"], $item_array_id))
                    {
                        $count = count($_SESSION["shopping_cart"]);
                        $item_array = array(
                            'item_id'     =>  $id,
                            'item_ref'     =>  $_GET["reference"],
                            'item_couleur'     =>  $_POST["hidden_color"],
                            'item_prix'    =>  $_POST["hidden_price"],
                            'item_quantite'   =>  $_POST["quantity"],
                            'item_dispo'   =>  $nbrDispo,
                            'item_poids'  =>  $_POST["hidden_poids"]
                        );
                        $_SESSION["shopping_cart"][$count] = $item_array;
                    }
                    else
                    {
                        echo '<script>alert("Item Already Added")</script>';
                    }
                }
                else
                {
                    $item_array = array(
                        'item_id'     =>  $id,
                        'item_ref'     =>  $_GET["reference"],
                        'item_couleur'     =>  $_POST["hidden_color"],
                        'item_prix'    =>  $_POST["hidden_price"],
                        'item_quantite'   =>  $_POST["quantity"],
                        'item_dispo'   =>  $nbrDispo,
                        'item_poids'  =>  $_POST["hidden_poids"]
                    );
                    $_SESSION["shopping_cart"][0] = $item_array;
                }
            }
            else {

            }
        }

        else {
            
        }
    }


    if(isset($_GET["action"]))
    {
        if($_GET["action"] == "delete")
        {
            foreach($_SESSION["shopping_cart"] as $keys => $values)
            {
                if($values["item_ref"] == $_GET["reference"])
                {
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>alert("Item Removed")</script>';
                    header("Location:article.php");
                }
            }
        }
    }
}



if(isset($_GET['reference']) AND !empty($_GET['reference'])){
    $getRef= $_GET['reference'];
    
    $recupArticle = $db->prepare('SELECT *from products where reference = ?');
    $recupArticle->execute(array($getRef));



    if($recupArticle->rowCount() > 0){
        //On récupère les champs de l'article choisi précedemment dans le tableau d'articles
        $article_Infos = $recupArticle->fetch();
        //Chaque champs est ramené avec la variable $article_Infos
        //On stocke ensuite 1 champ pour une variable 
        $id = $article_Infos['id'];
        $marque_Vêtement = $article_Infos['marque'];
        $nbr_Vêtement = $article_Infos['nombre_stock'];
        $categorie_Vêtement = $article_Infos['categorie'];
        $sousCat_Vêtement = $article_Infos['sous_categorie'];
        $prixAchat_Vêtement = $article_Infos['prix_achat_HT'];
        $prixVente_Vêtement = $article_Infos['prix_vente_HT'];
        $tauxTVA = $article_Infos['tauxTVA'];
        $prixTTC_Vêtement = $article_Infos['priceTTC'];
        $remise_Vêtement = $article_Infos['remise'];
        $poids_Vêtement = $article_Infos['poids'];
        $couleur_Vêtement = $article_Infos['couleur'];
        $description_Vêtement = $article_Infos['description'];
        $image_Vêtement = $article_Infos['image'];
        $recupTaille = $db->query("SELECT * from taille where id_ref = '$id'");

    }

 
}
?>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../design/menu.css">
    <link rel="stylesheet" type="text/css" href="../design/boutiqueFooter.css">
    <link rel="stylesheet" type="text/css" href="../design/menu.scss">
	<link rel="icon" type="image/png" href="../img/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../design/accueil.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Accueil </title>
    </head>
        <body id = "accueil-body">
            <header>
                <div id="brand">
                    <a  href="accueilCommun.php?accueil=1" ><img src="../img/logo.png" alt="LOGO" /></a>
                    <?php if(!isset($_SESSION["admin"])){ ?> <h1 class= "h1 text-presentation" >Bienvenue sur Stunning Outfit Shop !</h1> <?php } ?>
                    <?php if(isset($_SESSION["admin"])){ ?> <h1 class= "h1 text-presentation-admin" >Bienvenue sur  l'espace admin  de Stunning Outfit Shop !</h1> <?php } ?>
                    <div class="search-container">
                        <input type="text" placeholder="Search.." name="search">
                        <button class="bi bi-search"  type="submit"></button>
                    </div>
                    <?php if(isset($_SESSION["user"])){ ?>
                    <li class="dropdown">
                        <a class="bi bi-person-circle" style="font-size: 2rem; color: black;"><?php echo $_SESSION['prenom']; ?></a>

                        <div class="dropdown-content">          
                            <a style='color:white;' href="../espace_client/logout.php">Se déconnecter</a>
                            <a style='color:white;' href="../espace_client/profil.php">Consulter son profil</a>
                            <a style='color:white;' href="../espace_client/logout.php">Consulter ses commandes</a>
                        
                        </div>
                    </li>
                    <button class="glow-on-hover" onclick="window.location.href='../espace_client/panier.php';">Mon panier</button>
                    <?php } ?>

                    <?php if(isset($_SESSION["admin"])){ ?>
                    <button class="glow-on-hover-connexion" onclick="window.location.href='../espace_admin/logout.php';">Se déconnecter</button>
                    <?php } ?>
                    
                    <?php if(!isset($_SESSION["user"])&&!isset($_SESSION["admin"])){ ?>
                    <button class="glow-on-hover-connexion" onclick="window.location.href='connexion.php';">Se connecter </button>  
                    <?php } ?>
                </div>

            
                <!-- MENU CENTRALE-->
                <nav >
                    <ul>
                        <div class="menucontent">
                            <li><a class ="menu" href="accueilCommun.php?accueil=1" title="Aller à l'accueil">ACCUEIL</a></li>

                            <li> <a class = "menu" href="accueilCommun.php?homme=1" title="Homme">HOMME</a>
                                <ul class="submenu">
                                    <li><a href = "accueilCommun.php?categoriehomme=1&homme=1">joggings</a></li>
                                    <li><a href = "accueilCommun.php?categoriehomme=2&homme=1">shorts</a></li>
                                    <li><a href = "accueilCommun.php?categoriehomme=3&homme=1">Sweat</a></li>
                                    <li><a href = "accueilCommun.php?categoriehomme=4&homme=1">Polo</a></li>
                                    <li><a href = "accueilCommun.php?categoriehomme=5&homme=1">T-shirt</a></li>
                                    <li><a href = "accueilCommun.php?categoriehomme=6&homme=1">blouson</a></li>
                                </ul>
                            </li>
                            <li> <a class = "menu" href="accueilCommun.php?femme=1" title="Femme">FEMME</a>
                                <ul class="submenu">
                                    <li><a href = "accueilCommun.php?categoriefemme=1&femme=1">leggings</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=2&femme=1">joggings</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=3&femme=1">cycliste</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=4&femme=1">Sweat</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=5&femme=1">crop-top</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=6&femme=1">brassière</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=7&femme=1">T-shirt</a></li>
                                    <li><a href = "accueilCommun.php?categoriefemme=8&femme=1">blouson</a></li>                        
                                </ul>
                            </li>
                            <li> <a class = "menu" href="accueilCommun.php?remise=1" title="remise">REMISE</a></li>
                        </div>
                    </ul>
                </nav>

                <!-- caroussel_texte inutile 
                <div class="cadre">
                    <div class="centre">
                        <div class="carousel_texte">
                            <div class="changeHidden">
                                <div class="contenant">
                                    <div class="element">"Livraison gratuite pour les membres à partir de 20 € d'achat."</div>
                                    <div class="element">"Livraison standard à domicile en 2-4 jours."</div>
                                    <div class="element">"Un service impeccable !"</div>
                                    <div class="element">"Des produits de qualités"</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 -->
                

                <?php
                    if(isset($_GET['success'])){ ?>
                        <button onclick="window.location.href='../espace_client/checkout/index.php';">Mon panier</button>
                <?php } 
                    else { ?>    
                <?php }?> 
            </header>

        <div class="content-article">       
            <form method="POST" action="article.php?reference=<?= $_GET['reference']; ?>">
                <div class="slideDiv">
                    <div class="slider">
                        <?php echo "<img src='../img/".$categorie_Vêtement."/".$image_Vêtement."' alt =''/>"?>
                            
                        <div class='texte'>
                            <input type="hidden" name="hidden_id" value="<?= $id;?>" />
                            <h4><b>REFERENCE</b>: <?= $getRef;?> </h4> <input type="hidden" name="hidden_ref" value="<?= $getRef;?>" />
                            <h4><b>PRIX</b>: <?= $prixTTC_Vêtement?> </h4> <input type="hidden" name="hidden_price" value="<?= $prixTTC_Vêtement?>" />
                            <h4><b>COULEUR</b>: <?=$couleur_Vêtement?> </h4> <input type="hidden" name="hidden_color" value="<?=$couleur_Vêtement?>" />
                            <h4><b>POIDS</b>: <?=$poids_Vêtement?> g </h4> <input type="hidden" name="hidden_poids" value="<?=$poids_Vêtement?>" />
                            <h4><b>TAILLE</b>: 
                            <select name="taille" id="taille">
                                <option  selected disabled hidden>L</option>
                                    <?php 
                                         while($article = $recupTaille->fetch()) 
                                        {
                                            echo "<option value=".$article['taille'].">".$article['taille']."</option>";
                                        }
                                    ?> 
                            </select></h4>
                            <h4><b>QUANTITE</b>: <input value=1 name="quantity" min='0' max='9999' type='number' class='quantite'></h4>

    
                            <br>
                            <br>
                            <br>

                            <?php if(isset($_SESSION["user"])) {?>
                            
                            <button type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" onClick="AjoutArticle()" >Ajouter au panier</button>
                            <?php } ?>
                            <?php if(!isset($_SESSION["user"])) { ?>
                            <button class="btn btn-success" type="submit" formaction='../espace_commun/connexion.php'>Connectez-vous</button>
                            <?php }?>
                    </div>
                </div>
            </form>
                <script type="text/javascript">
                function AjoutArticle() {
                    alert("Article ajouté");
                }
                </script>
                <!--
                <div class="sliderRight"><i class="fa-solid fa-angle-right"></i></div>
                <div class="sliderLeft"><i class="fa-solid fa-angle-left"></i></div>
                <div class="miniPics"></div>
                -->
        </div>  

        <div class="nbimg">
            <script type='text/javascript'>
                let imgNum = -2;
            </script>
        </div>

        <div class='description'>
            <p>
                <h3><b><u>Description Article</u></b></h3>
                <br>
                <h5><?= $description_Vêtement?> </h5>
            </p>
        </div>
    </div>

    <section>                        
        <?php require("../src/boutiqueFooter.php");?>
    </section>  
    <script src ="../js/script.js"> </script>
    </body>
</html>


