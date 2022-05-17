<?php 
session_start();
require('../src/connect.php');
$serveur="localhost";
$login="root";
$mdp="";
$bd = "projet-ecommerce-v12";
$tables = "membres";

$total = $_SESSION["total"]; 

if(isset($_SESSION["user"])){

    $requser = $db->prepare("SELECT * FROM membres WHERE email = ?");
    $requser->execute(array($_SESSION['user']));
    $user = $requser->fetch();

    $idclient = $user["id_client"];

    $requser2 = $db->prepare("SELECT count(DISTINCT num_facture)+1 FROM factures WHERE id_client = ?");
    $requser2->execute(array($idclient));
    $factures = $requser2->fetchColumn();

    $factures_format = sprintf("%'03d", $factures);
    $date = date("Y");
    $num_facture = $date.$factures_format;
}

foreach($_SESSION["shopping_cart"] as $keys => $values){
    $ref = $values['item_id'];
    $quantite = $values['item_dispo'] - $values['item_quantite'];
    
    $connexion=mysqli_connect($serveur,$login,$mdp)
    or die("Connexion impossible au serveur $serveur pour $login");

    $conn = mysqli_select_db($connexion,$bd)
    or die("Impossible d'accéder à la base de données");

    $reqfacture = "INSERT INTO `factures` (`id_facture`,`num_facture`, `id_client`, `prix`, `reference`, `quantite`) VALUES (NULL, '$num_facture', '$idclient', '$total', '$ref', '$quantite');";

    $updateTaille = $db->prepare('UPDATE taille SET dispo = ? WHERE id_ref = ?');
    $updateTaille->execute(array($quantite, $ref));

    mysqli_query($connexion,$reqfacture);
}


$requete3 ="SELECT `email` FROM `membres` WHERE id_client = '$idclient'";
$resultat3 = mysqli_query($connexion,$requete3);
$result3 = implode(mysqli_fetch_row($resultat3));

if((mysqli_num_rows($resultat3)!=0)){ //Si le login existe

     unset($_SESSION["shopping_cart"]);
     unset($_SESSION["total"]);
     header("Location:../espace_commun/accueilCommun.php?accueil=1");
}

?>