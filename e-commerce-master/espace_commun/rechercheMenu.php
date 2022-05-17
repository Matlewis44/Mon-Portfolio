<?php
 /**
* Permet de faire une recherche selon les caractéristiques 
* Distingue les recherches entre hommes et femmes 
* Fait par krishnath 
*/

require('../src/connect.php');
if (isset($_GET['homme'])||isset($_GET['femme'])){
    if (isset($_GET['homme']) && $_GET['homme'] == 1) $sexe = 'homme';
    if (isset($_GET['femme']) && $_GET['femme'] == 1) $sexe = 'femme';


    if (!isset($_GET['categoriehomme']) || !isset($_GET['categoriefemme'])){
        if(isset($_POST['prix'])||isset($_POST['marque'])||isset($_POST['couleur'])){
            if(isset($_POST['prix'])) {
                $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and priceTTC > $prixmin and priceTTC < $prixmax");
                $nbrcategorie = $res->fetchColumn();
                $categorie = $db->prepare("SELECT * from products where categorie = ? and priceTTC > ? and priceTTC < ?");
                $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                $categorie->execute(array($sexe,$prixmin,$prixmax));

            }
            if(isset($_POST['marque'])||isset($_POST['couleur'])) {
                if(isset($_POST['marque'])){
                    $selected = $_POST['marque'];
                    $recherche = 'marque';
                } 
                if(isset($_POST['couleur'])){
                    $selected = $_POST['couleur'];
                    $recherche = 'couleur';
                } 
                if(isset($_POST['prix'])) {
                    $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                    $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and priceTTC > $prixmin and priceTTC < $prixmax and $recherche = '$selected'");
                    $nbrcategorie = $res->fetchColumn();
                    $categorie = $db->prepare("SELECT * from products where categorie = ? and priceTTC > ? and priceTTC < ? and $recherche = ?");
                    $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                    $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                    $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                    $categorie->bindParam('?', $selected, PDO::PARAM_STR, 12);
                    $categorie->execute(array($sexe,$prixmin,$prixmax,$selected));
                }
                else{
                    $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and $recherche = '$selected'");
                    $nbrcategorie = $res->fetchColumn();
                    $categorie = $db->prepare("SELECT * from products where categorie = ? and $recherche = ?");
                    $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                    $categorie->bindParam('?', $selected, PDO::PARAM_STR, 12);
                    $categorie->execute(array($sexe,$selected));
                }

            }
            if(isset($_POST['marque'])&& isset($_POST['couleur'])) {
                $marque = $_POST['marque']; 
                $couleur = $_POST['couleur'];

                $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and marque = '$marque' and couleur = '$couleur'");
                $nbrcategorie = $res->fetchColumn();
                $categorie = $db->prepare("SELECT * from products where categorie = ? and marque = ? and couleur = ?");
                $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                $categorie->bindParam('?', $marque, PDO::PARAM_STR, 12);
                $categorie->bindParam('?', $couleur, PDO::PARAM_STR, 12);
                $categorie->execute(array($sexe,$marque,$couleur));
            }

            if(isset($_POST['marque'])&& isset($_POST['couleur']) && isset($_POST['prix'])) {
                $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                $marque = $_POST['marque']; 
                $couleur = $_POST['couleur'];

                $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and priceTTC > $prixmin and priceTTC < $prixmax and marque = '$marque' and couleur = '$couleur'");
                $nbrcategorie = $res->fetchColumn();
                $categorie = $db->prepare("SELECT * from products where categorie = ? and priceTTC > ? and priceTTC < ? and marque = ? and couleur = ?");
                $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                $categorie->bindParam('?', $marque, PDO::PARAM_STR, 12);
                $categorie->bindParam('?', $couleur, PDO::PARAM_STR, 12);
                $categorie->execute(array($sexe,$prixmin,$prixmax,$marque,$couleur));
            }
        }
        

        else {
            $res = $db->query("SELECT count(*) from products where categorie ='$sexe'");
            $nbrcategorie = $res->fetchColumn();
            $categorie = $db->prepare("SELECT * from products where categorie = ?");
            $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
            $categorie->execute(array($sexe));
        }
    }   

    if (isset($_GET['categoriehomme'])||isset($_GET['categoriefemme'])){
        if (isset($_GET['categoriehomme'])) $categorieHF = $_GET['categoriehomme'];
        if (isset($_GET['categoriefemme'])) $categorieHF = $_GET['categoriefemme'];

        if (isset($_GET['homme'])) $tableau_sous_categorie = array(1 => "jogging", 2 => "short", 3 => "Sweat", 4 => "Polo", 5 => "T-shirt", 6 => "blouson");
        if (isset($_GET['femme'])) $tableau_sous_categorie = array(1 => "legging", 2 => "jogging", 3 => "cycliste", 4 => "Sweat", 5 => "crop-top", 6=> "brassière", 7=> "T-shirt", 8 => "blouson");

        foreach( $tableau_sous_categorie as $k => $v ){

            if ($k == $categorieHF){

                $sous_categorie = $v;

                if(isset($_POST['prix'])||isset($_POST['marque'])||isset($_POST['couleur'])){
                    if(isset($_POST['prix'])) {
                        $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                        $res = $db->query("SELECT count(*) from products where categorie ='$sexe' and sous_categorie ='$sous_categorie' and priceTTC > $prixmin and priceTTC < $prixmax");
                        $nbrsouscategorie = $res->fetchColumn();
                        $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? and priceTTC > ? and priceTTC < ?");
                        $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                        $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                        $categorie->execute(array($sexe,$sous_categorie,$prixmin,$prixmax));
    
                    }
                    if(isset($_POST['marque'])||isset($_POST['couleur'])) {
                        if(isset($_POST['marque'])){
                            $selected = $_POST['marque'];
                            $recherche = 'marque';
                        } 
                        if(isset($_POST['couleur'])){
                            $selected = $_POST['couleur'];
                            $recherche = 'couleur';
                        } 
                        if(isset($_POST['prix'])) {
                            $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                            $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and sous_categorie ='$sous_categorie' and priceTTC > $prixmin and priceTTC < $prixmax and $recherche = '$selected'");
                            $nbrsouscategorie = $res->fetchColumn();
                            $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? and priceTTC > ? and priceTTC < ? and $recherche = ?");
                            $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                            $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                            $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                            $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                            $categorie->bindParam('?', $selected, PDO::PARAM_STR, 12);
                            $categorie->execute(array($sexe,$sous_categorie,$prixmin,$prixmax,$selected));
                        }
                        else{
                            $res = $db->query("SELECT count(*) from products where categorie ='$sexe'and sous_categorie ='$sous_categorie' and $recherche = '$selected'");
                            $nbrsouscategorie = $res->fetchColumn();
                            $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? and $recherche = ?");
                            $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                            $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                            $categorie->bindParam('?', $selected, PDO::PARAM_STR, 12);
                            $categorie->execute(array($sexe,$sous_categorie,$selected));
                        }
    
                    }
                    if(isset($_POST['marque'])&& isset($_POST['couleur'])) {
                        $marque = $_POST['marque']; 
                        $couleur = $_POST['couleur'];
    
                        $res = $db->query("SELECT count(*) from products where categorie ='$sexe' and sous_categorie ='$sous_categorie' and marque = '$marque' and couleur = '$couleur'");
                        $nbrsouscategorie = $res->fetchColumn();
                        $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? and marque = ? and couleur = ?");
                        $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $marque, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $couleur, PDO::PARAM_STR, 12);
                        $categorie->execute(array($sexe,$sous_categorie,$marque,$couleur));
                    }
    
                    if(isset($_POST['marque'])&& isset($_POST['couleur']) && isset($_POST['prix'])) {
                        $prixmin = $_POST['prix']; $prixmax = $prixmin +50;
                        $marque = $_POST['marque']; 
                        $couleur = $_POST['couleur'];
    
                        $res = $db->query("SELECT count(*) from products where categorie ='$sexe' and sous_categorie ='$sous_categorie' and priceTTC > $prixmin and priceTTC < $prixmax and marque = '$marque' and couleur = '$couleur'");
                        $nbrsouscategorie = $res->fetchColumn();
                        $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? and priceTTC > ? and priceTTC < ? and marque = ? and couleur = ?");
                        $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $prixmin, PDO::PARAM_INT, 5);
                        $categorie->bindParam('?', $prixmax, PDO::PARAM_INT, 5);
                        $categorie->bindParam('?', $marque, PDO::PARAM_STR, 12);
                        $categorie->bindParam('?', $couleur, PDO::PARAM_STR, 12);
                        $categorie->execute(array($sexe, $sous_categorie,$prixmin,$prixmax,$marque,$couleur));
                    }
                }

                else {
                    //Afficher le nombre d'articles pour chaque catégorie et sous catégorie 
                    $res = $db->query("SELECT count(*) from products where categorie ='$sexe' and sous_categorie ='$sous_categorie'");
                    $nbrsouscategorie = $res->fetchColumn();
                    
                    $categorie = $db->prepare("SELECT * from products where categorie = ? and sous_categorie = ? ");
                    $categorie->bindParam('?', $sexe, PDO::PARAM_STR, 12);
                    $categorie->bindParam('?', $sous_categorie, PDO::PARAM_STR, 12);
                    $categorie->execute(array($sexe,$sous_categorie));

                }

            }
        }
    }
    


    if (isset($sous_categorie)){
        echo " <h3 id='searchcategorie' style='padding-bottom: 40px; padding-top: 40px;'>Vetement / ".$sexe." / ".$sous_categorie." (".$nbrsouscategorie.")</h3>";
    }
    else {
        echo "<h3 id='searchcategorie' style='padding-bottom: 40px; padding-top: 40px;'>Vetement / ".$sexe." (".$nbrcategorie.")</h3>";
    }


    echo " <div id='projets'>";
    while($article = $categorie->fetch()){
        $prixinitial = $article['priceTTC'];
        $prixremise = $article['priceTTC']*($article['remise']/100);
        $prixTTC = $article['priceTTC'] - $prixremise ;
        $_SESSION['article'] = $article['reference'];
        echo "
            <div class='projet'>"?>
                <!--a href='#' title='Voir le premier projet' -->
                <a href = '../espace_commun/article.php?reference=<?= $article['reference']; ?>' 
                    style='color:black; text-decoration: none;'>
                    <?="<div class='picture'>";
                    if ($sexe == "homme"){
                        echo "<img src='../img/homme/".$article['image']."' alt =''/>";
                    }

                    if ($sexe == "femme"){
                        echo "<img src='../img/femme/".$article['image']."' alt =''/>";
                    }
    echo             "</div>
                    <span class='reference'style='color:black;'>".$article['reference']." </span>"?>

                    <?php if($article['remise'] >0 ){
                        echo"  <span class='prix'style='color:black;'> <strike>". $prixinitial." €</strike> ".$article['remise']."% </span> 
                        <span class='prix'style='color:green;'>". $prixTTC." €</span>";
                    }else{
                        echo"<span class='prix'style='color:black;'>". $prixTTC." €</span>";
                    }?> <?="
                </a>
                </div>";

    }
    echo "</div>";       
}

if (isset($_GET['search'])){ 
            
    echo " <h3 id='searchcategorie' style='padding-bottom: 40px; padding-top: 40px;'>Vetement / ".$_GET['search']." </h3>";
    $carac = $_GET['search'];
    $res = $db->query("SELECT count(*) from products where couleur = '$carac' or marque = '$carac' or reference = '$carac' or description = '$carac' or categorie = '$carac' or sous_categorie = '$carac'");
    $nbrArticle = $res->fetchColumn();         

    $recupArticle = $db->prepare("SELECT * from products 
                                where couleur = ? or marque = ? or reference = ? or description = ? or categorie = ? or sous_categorie = ? 
                             ");
    $recupArticle->execute(array($carac,$carac,$carac,$carac,$carac,$carac));    

    echo " <div id='projets'>";
    foreach($recupArticle as $article){ 

        $prixinitial = $article['priceTTC'];
        $prixremise = $article['priceTTC']*($article['remise']/100);
        $prixTTC = $article['priceTTC'] - $prixremise ;
        $_SESSION['article'] = $article['reference'];

         echo "
            <div class='projet'>"?>
                <!--a href='#' title='Voir le premier projet' -->
                <a href = '../espace_commun/article.php?reference=<?= $article['reference']; ?>' 
                    style='color:black; text-decoration: none;'>
                    <?="<div class='picture'>";
                    if ($article['categorie'] == "Homme"){
                       
                        echo "<img src='../img/homme/".$article['image']."' alt =''/>";
                    }

                    if ($article['categorie'] == "Femme"){
                        echo "<img src='../img/femme/".$article['image']."' alt =''/>";
                    }
    echo             "</div>
                    <span class='reference'style='color:black;'>".$article['reference']." </span>"?>

                    <?php if($article['remise'] >0 ){
                        echo"  <span class='prix'style='color:black;'> <strike>". $prixinitial." €</strike> ".$article['remise']."% </span> 
                        <span class='prix'style='color:green;'>". $prixTTC." €</span>";
                    }else{
                        echo"<span class='prix'style='color:black;'>". $prixTTC." €</span>";
                    }?> <?="
                </a>
                </div>";

    }
    echo "</div>";       
}


