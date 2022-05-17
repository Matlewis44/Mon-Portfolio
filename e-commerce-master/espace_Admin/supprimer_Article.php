<?php
session_start();
require('../src/connect.php');

if(isset($_GET['reference']) AND !empty($_GET['reference'])){
    $getRef= $_GET['reference'];
    $recupArticle = $db->prepare('SELECT *from products where reference = ?');
    $recupArticle->execute(array($getRef));

    if($recupArticle->rowCount() > 0){
        $supprim_Article = $db->prepare('DELETE FROM products where reference = ?');
        $supprim_Article->execute(array($getRef));
        header("Location: articles.php?Cet-article-a-été-supprimer");
    }else{
        echo "Aucun article n'a été trouvé";
    }

}else{
    "L'identifiant n'a pas été récupéré ";
}
?>