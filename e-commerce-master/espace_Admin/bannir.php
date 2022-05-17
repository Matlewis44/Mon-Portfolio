<?php
session_start();
require('../src/connect.php');

if(isset($_GET['email']) AND !empty($_GET['email'])){
    $getEmail= $_GET['email'];
    $recupUser = $db->prepare('SELECT *from membres where email = ?');
    $recupUser->execute(array($getEmail));

    if($recupUser->rowCount() > 0){
        $blockUser = $db->prepare(' UPDATE membres SET blocked = 1 where email = ?');
        $blockUser->execute(array($getEmail));
        header("Location: membres.php?Cet-utilisateur-a-été-banni");
    }else{
        echo "Aucun membre n'a été trouvé";
    }

}else{
    "L'identifiant n'a pas été récupéré ";
}
?>

