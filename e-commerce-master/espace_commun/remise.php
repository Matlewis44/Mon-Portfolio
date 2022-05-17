<?php
 /**
* Permet de faire une recherche selon les caractéristiques 
* Distingue les recherches entre hommes et femmes 
* Fait par krishnath 
*/

require('../src/connect.php');
if (isset($_GET['remise'])){

    $res = $db->query("SELECT count(*) from products where  remise > 0");
    $nbrremise = $res->fetchColumn();
    $categorie = $db->prepare("SELECT * from products where  remise > 0");
    $categorie->execute();

    echo " <h3 id='searchcategorie' style='padding-bottom: 40px; padding-top: 40px;'>Vetement / remise (".$nbrremise.")</h3>";



    echo " <div id='projets'>";
    while($article = $categorie->fetch()){
        //+50 à enlever juste pour test dans recherche article
        $prixinitial = $article['priceTTC'];
        $prixremise = $article['priceTTC']*($article['remise']/100);
        $prixTTC = $article['priceTTC'] - $prixremise ;
        echo "
            <div class='projet'>
                <a href='#' title='Voir le premier projet' >
                    <div class='picture'>";
                    if ($article['categorie'] == "homme"||$article['categorie'] == "Homme"){
                        echo "<img src='../img/homme/".$article['image']."' alt =''/>";
                    }

                    if ($article['categorie'] == "Femme"||$article['categorie'] == "femme"){
                        echo "<img src='../img/femme/".$article['image']."' alt =''/>";
                    }
    echo             "</div>
                    <span class='reference'style='color:black;'>".$article['reference']." </span>
                    <span class='prix'style='color:black;'> <strike>". $prixinitial." €</strike> ".$article['remise']."% </span> 
                    <span class='prix'style='color:green;'>". $prixTTC." €</span>
                </a>
            </div>";

    }
    echo "</div>";         

}
