<?php
session_start();
if(isset($_GET['dcx'])) {
	$dcx=$_GET['dcx'];
	if(strcmp($dcx,"Déconnexion")==0){
		session_destroy();
		unset($_SESSION['login']);
		unset($_SESSION['groupe']);
		unset($_SESSION['date']);
		header('Location: index.php');
	}
	else header('Location: index.php?error=400');
}
else header('Location: index.php?error=400');
?>
