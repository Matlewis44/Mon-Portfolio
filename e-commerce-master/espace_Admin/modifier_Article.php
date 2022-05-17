<?php
session_start();
require('../src/connect.php');

if(isset($_GET['reference']) AND !empty($_GET['reference'])){
    $getRef= $_GET['reference'];
    
    $recupArticle = $db->prepare('SELECT *from products where reference = ?');
    $recupArticle->execute(array($getRef));

    if($recupArticle->rowCount() > 0){
        //On récupère les champs de l'article choisi précedemment dans le tableau d'articles
        $article_Infos = $recupArticle->fetch();
        //Chaque champs est ramené avec la variable $article_Infos
        //On stocke ensuite 1 champ pour une variable 
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

        if(isset($_POST['ok'])){
            $marque_saisi = $_POST['marque'];
            $reference_saisi = $_POST['reference'];
            $stock_saisi = $_POST['stock'];
            $categorie_saisi = $_POST['categorie'];
            $sousCat_saisi = $_POST['sousCat'];
            $prixVente_saisi = $_POST['prix_vente'];
            $TVA_saisi = $_POST['TVA'];
            $prixTTC_saisi = $_POST['priceTTC'];
            $remise_saisi = $_POST['remiseVêtement'];
            $poids_saisi = $_POST['poids'];
            $couleur_saisi = $_POST['couleur'];
            $description_saisi = $_POST['description'];

            
            if(!empty($marque_saisi) ){
                $updateMarque = $db->prepare('UPDATE products SET marque = ? WHERE reference = ?');
                $updateMarque->execute(array($marque_saisi, $getRef));
            }
            elseif (!empty($stock_saisi) ) {
                $updateStock = $db->prepare('UPDATE products SET nombre_stock = ? WHERE reference = ?');
                $updateStock->execute(array($stock_saisi, $getRef));
            }
            elseif (!empty($categorie_saisi) ) {
                $updateCategorie = $db->prepare('UPDATE products SET categorie = ? WHERE reference = ?');
                $updateCategorie->execute(array($categorie_saisi, $getRef));
            } 
            elseif (!empty($sousCat_saisi) ) {
                $updateSousCat = $db->prepare('UPDATE products SET sous_categorie = ? WHERE reference = ?');
                $updateSousCat->execute(array($sousCat_saisi, $getRef));
            }
            elseif (!empty($prixVente_saisi) ) {
                $updatePrice = $db->prepare('UPDATE products SET prix_vente_HT = ? WHERE reference = ?');
                $updatePrice->execute(array($prixVente_saisi, $getRef));
            }
            elseif (!empty($TVA_saisi) ) {
                $updateTVA = $db->prepare('UPDATE products SET prix_vente_HT = ? WHERE reference = ?');
                $updateTVA->execute(array($TVA_saisi, $getRef));
            }
            elseif (!empty($prixTTC_saisi) ) {
                $updatePrixTTC = $db->prepare('UPDATE products SET priceTTC = ? WHERE reference = ?');
                $updatePrixTTC->execute(array($prixTTC_saisi, $getRef));
            }
            elseif (isset($remise_saisi) ) {
                $remise = $db->prepare('UPDATE products SET remise = ? WHERE reference = ?');
                $remise->execute(array($remise_saisi, $getRef));
            }
            elseif (!empty($poids_saisi) ) {
                $updatePoids = $db->prepare('UPDATE products SET poids = ? WHERE reference = ?');
                $updatePoids->execute(array($poids_saisi, $getRef));
            }
            elseif (!empty($couleur_saisi) ) {
                $updateColor = $db->prepare('UPDATE products SET couleur = ? WHERE reference = ?');
                $updateColor->execute(array($couleur_saisi, $getRef));
            }
            elseif (!empty($description_saisi) ) {
                $updateDescription = $db->prepare('UPDATE products SET description = ? WHERE reference = ?');
                $updateDescription->execute(array($description_saisi, $getRef));
            }

            elseif (isset($_FILES['img']) AND $_FILES['img']['error'] == 0 ) {
                
                if ($_FILES['img']['size'] <= 1000000) {
                    // Testons si l'extension est autorisée
                    $infosfichier = pathinfo($_FILES['img']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                    if (in_array($extension_upload, $extensions_autorisees)){
                        // On peut valider le fichier et le stocker définitivement
                        $nom_Image = time().basename($_FILES['img']['name']);
                        if($categorie_Vêtement == 'Homme'){
                            move_uploaded_file($_FILES['img']['tmp_name'], '../img/homme/' .$nom_Image);
                        }
                        elseif($categorie_Vêtement == 'Femme') { move_uploaded_file($_FILES['img']['tmp_name'], '../img/femme/' .$nom_Image);}
                        echo "L'envoi a bien été effectué !";
                    }
                }
                $updateImage = $db->prepare('UPDATE products SET image = ? WHERE reference = ?');
                $updateImage->execute(array($nom_Image, $getRef));
            }
           
            header('location: articles2.php?Article-modifié-avec-succès');
            
        }
        
        
    }else{
        echo "Aucun article n'a été trouvé";
    }

}else{
    "La référence n'a pas été récupéré ";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/stylesheet.css" >
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Modifier l'article</title>
</head>
<body>
    <!-- Formulaire d'affichage des valeurs de l'article sélectionné-->
    <form method="POST" action="" enctype="multipart/form-data">
        <fieldset> 
            <legend style="font-weight:bold; font-size:1.3em;">Voici les valeurs de l'article sélectionné</legend>
            <table style="text-align: initial;">
                    <tr>
                        <td> Marque :</a> </td> 
                        <td> <?= $marque_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Réference : </td> 
                        <td> <?= $getRef?> </td>
                    </tr>
                    <tr>
                        <td> Nombre d'articles en stock : </td> 
                        <td> <?= $nbr_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Catégorie : </td> 
                        <td> <?= $categorie_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Sous-catégorie : </td> 
                        <td> <?= $sousCat_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Le prix d'achat HT : </td> 
                        <td> <?= $prixAchat_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Le prix de vente HT : </td> 
                        <td> <?= $prixVente_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Taux TVA (en %) : </td> 
                        <td> <?= $tauxTVA?> </td>
                    </tr>
                    <tr>
                        <td> Le prix TTC : </td> 
                        <td> <?= $prixTTC_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> La remise appliquée (en %) : </td> 
                        <td> <?= $remise_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Poids de l'article (en g) : </td> 
                        <td> <?= $poids_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Couleur : </td> 
                        <td> <?= $couleur_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Détails du produit : </td> 
                        <td> <?= $description_Vêtement?> </td>
                    </tr>
                    <tr>
                        <td> Image actuelle : </td> 
                        <td> <?= $image_Vêtement?> </td>
                    </tr>
                    
                </table>
        </fieldset>
    </form> <br><br>

    <!-- Formulaire de modification des valeurs de l'article sélectionné-->
    <form method="POST" action="" enctype="multipart/form-data">
        <fieldset> <legend style="font-weight:bold; font-size:1.3em;">Modifier les valeurs de cet article</legend>
            <table style="text-align: initial;">
                <tr>
                    <td> Marque : </td> 
                    <td> <input type="text" name = "marque" placeholder="ex: Nike, Addidas" autofocus> </td>
                </tr>
                <tr>
                    <td> Réference : </td> 
                    <td><b style="color:red;"> Cette valeur est inchangeable </b></td>
                </tr>
                <tr>
                    <td> Nombre d'articles en stock : </td> 
                    <td> <input type="number" name = "stock" placeholder="donner un nombre"> </td>
                </tr>
                <tr>
                    <td> Catégorie : </td> 
                    <td> <input type="text" name = "categorie" placeholder="Homme ou Femme"> </td>
                </tr>
                <tr>
                    <td> Sous-catégorie : </td> 
                    <td> <input type="text" name = "sousCat" placeholder="Jogging, brassière, short"> </td>
                </tr>
                <tr>
                    <td> Le prix de vente (hors taxe) : </td> 
                    <td> <input type="float" name = "prix_vente" placeholder="ex: 37.50"> </td>
                </tr>
                <tr>
                    <td> Taux TVA (en %) : </td> 
                    <td> <input type="float" name = "TVA" placeholder="ex: 20"> </td>
                </tr>

                <tr>
                    <td> Le prix de l'article (TTC en €) : </td> 
                    <td> <input type="float" name = "priceTTC" placeholder="ex: 50.75"> </td>
                    
                </tr>
                <tr>
                    <td style="color:red;"> Prix TTC = (Prix HT vente) x (1 + Taux TVA )</td>                     
                </tr>

                <tr>
                    <td> La remise appliquée (en %) : </td>
                    <td> <input type="number" name= "remiseVêtement" placeholder="ex: 10"> </td>
                </tr>
                <tr>
                    <td> Poids de l'article : </td> 
                    <td> <input type="float" name = "poids" placeholder="poids en gramme"> </td>
                </tr>
                <tr>
                    <td> Couleur : </td> 
                    <td> <input type="text" name = "couleur"placeholder="Noir, Bleu"> </td>
                </tr>
                <tr>
                    <td> Détails du produit : </td> 
                    <td> <textarea name = "description"></textarea> </td>
                </tr>
                <tr>
                    <td style='padding-top:15px;'> </td>
                    <td style='padding-top:15px;padding-bottom:5px;'><p> Veuillez télécharger une image et la nommer convenablement au préalable </p></td>
                </tr>
                <tr>
                    <td> Image de l'article : </td> 
                    <td> <input type="file" name="img" /> </td>
                    <td>  </td>
                </tr>               
                <tr>
                    <td> <input style="margin-top:30px;" name='res' type='reset' value='Annuler'/></td> 
                    <td><input style="margin-top:30px;" name='ok' type='submit' value='Valider'onclick = "successModif()"/></td>
                </tr>
            </table>
        </fieldset>
    </form>
    <br>
    <button onclick="window.location.href='articles.php';">Retour sur la liste des articles</button>
    <button style="margin-left:10px;" onclick="window.location.href='../espace_commun/accueilCommun.php?accueil=1';">Revenir à l'accueil</button>

    <script src="script.js"></script>
</body>
</html>