 <?php
// PAGE POUR AJOUTER UN ARTICLE
session_start();
require('../src/connect.php');
require_once('mesFonctions.php');
logoAdmin();
initMenu2Admin();

if(!$_SESSION['mdp']){
    header('location: index.php');
}

if(isset($_POST['ok'])){

    foreach ($_POST as $k => $v) $$k = $v;
    if (!empty($marque) AND !empty($reference) AND !empty($stock) AND 
        !empty($categorie) AND !empty($sousCat) AND !empty($prix_achat_HT) AND 
        !empty($prix_vente_HT) AND !empty($priceTTC) AND !empty($TVA) AND 
        !empty($couleur) AND !empty($description)  AND 
        !empty($poids)) {
        
            if (isset($_FILES['img']) AND $_FILES['img']['error'] == 0 ) {
                // Testons si le fichier n'est pas trop gros
                if ($_FILES['img']['size'] <= 1000000) {
                    // Testons si l'extension est autorisée
                    $infosfichier = pathinfo($_FILES['img']['name']);
                    $extension_upload = $infosfichier['extension'];
                    $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png', 'jfif');
                    if (in_array($extension_upload, $extensions_autorisees)){
                        // On peut valider le fichier et le stocker définitivement
                        $nom_Image = time().basename($_FILES['img']['name']);
                        if($categorie == 'Homme'){
                            move_uploaded_file($_FILES['img']['tmp_name'], '../img/homme/' .$nom_Image);
                        }
                        elseif($categorie == 'Femme') { move_uploaded_file($_FILES['img']['tmp_name'], '../img/femme/' .$nom_Image);}
                        echo "L'envoi a bien été effectué !";
                    }
                }
            }
        // REFERENCE DEJA UTILISEE
		$req = $db->prepare("SELECT count(*) as numberReference FROM products WHERE reference = ?");
		$req->execute(array($reference));

		while($reference_verification = $req->fetch()){

			if($reference_verification['numberReference'] != 0){
                header('location: publier_Article.php?error=1&message=Cette reference est déjà utilisée pour un autre article. Pour rappel, la réference d\'un article doit être unique');
                echo "<p style = 'padding: 10px;margin: 30px 0;color:blue; border-radius: 5px; background-color: red;'> 
                        <b>Cette reference est déjà utilisée pour un autre article. Pour rappel, la réference d\'un article doit être unique.</b>
                    </p> ";
                exit();

            }

		}

            $insererArticle = $db->prepare('INSERT INTO products(marque, reference, nombre_stock, categorie, sous_categorie, 
            prix_achat_HT, prix_vente_HT, priceTTC, tauxTVA, poids,couleur, description, image) VALUES(?,?,?, ?,?,?, ?,?,?, ?,?,?)');
            $insererArticle->execute(array($marque, $reference, $stock,$categorie, $sousCat, $prix_achat_HT, $prix_vente_HT, $priceTTC, $TVA, 
                                    $poids, $couleur, $description, 
                                     $nom_Image));

            echo "L'article a bien été ajouté avec succès";
            header('Location: articles.php');
      
    }else{
        echo "<p style = 'color:red;'> <b>Veuillez renseigner tous les champs.</b></p> ";
    } 
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/stylesheet.css" >
    <link rel="icon" type="image/png" href="../img/favicon.png">
    
    <title>Ajouter un article</title>
</head>
<body>
    <?php if(isset($_GET['error'])){

            if(isset($_GET['message'])) {
                echo'<div class="alert error">'.htmlspecialchars($_GET['message']).'</div>';
            }

        } 
    ?>
    <form method="POST" action="" enctype="multipart/form-data">
    <legend style="font-weight:bold; font-size:1.3em;">Ajouter un nouvel Article</legend>
        <fieldset class ="content-publier">
            <table class="table-publier">
                <tr>
                    <td> Marque : </td> 
                    <td> <input type="text" name = "marque" placeholder="ex: Nike, Addidas" autofocus> </td>
                </tr>
                <tr>
                    <td> Réference : </td> 
                    <td> <input type="text" name = "reference" placeholder="ex: nikeJogBlack1"> </td>
                </tr>
                <tr>
                    <td> Nombre d'articles en stock : </td> 
                    <td> <input type="number" name = "stock" placeholder="ex: 10"> </td>
                </tr>
                <tr>
                    <td> Catégorie : </td> 
                    <td> <select name="categorie" required>
                            <option value="">Choisir...</option>
                            <option>Homme</option>
                            <option>Femme</option>
                        </select> </td>
                </tr>
                <tr>
                    <td> Sous-catégorie : </td> 
                    <td> <input type="text" name = "sousCat" placeholder="Jogging, brassière, short"> </td>
                </tr>
                <tr>
                    <td> Le prix d'achat (HT en €) : </td> 
                    <td> <input type="float" name = "prix_achat_HT" placeholder="37.50"> </td>
                </tr>
                <tr>
                    <td> Le prix de vente (HT en €) : </td> 
                    <td> <input type="float" name = "prix_vente_HT" placeholder="ex: 37.50"> </td>
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
                    <td> Poids de l'article (en g): </td> 
                    <td> <input type="float" name = "poids" placeholder="poids en gramme"> </td>
                </tr>
                <tr>
                    <td> Couleur: </td> 
                    <td> <input type="text" name = "couleur"placeholder="Noir, Bleu"> </td>
                </tr>
                <tr>
                    <td> Détails du produit : </td> 
                    <td> <textarea name = "description"></textarea> </td>
                </tr>

                <tr>
                <td style='padding-top:15px;padding-bottom:5px;'><p> Veuillez télécharger une image et la nommer convenablement au préalable </p></td>
                    <td style='padding-top:15px;'> </td>
                </tr>
                <tr>
                    <td> Image de l'article : </td> 
                    <td> <input type="file" name="img" /> </td>
                    <td>  </td>
                </tr>
                
                <tr>
                    <td style='padding-top:15px;'> <input name='res' type='reset' value='Annuler'/></td> 
                    <td style='padding-top:15px;'><input name='ok' type='submit' value='Valider'onclick = "success()"/></td>
                </tr>
            </table>
        </fieldset>
    </form><br>
    <button class = "accueil-publierArticle" style="margin-left: 35%;"onclick="window.location.href='../espace_commun/accueilCommun.php?accueil=1';">Revenir à l'accueil</button>
    <button class = "ListeArticle-button" style="margin-left:50px;"onclick="window.location.href='articles.php';" title='Voir tous les articles'>Tous les articles</button>
    
    <script src="script.js"></script>
</body>
</html>