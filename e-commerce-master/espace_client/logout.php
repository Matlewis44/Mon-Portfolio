<?php

	session_start(); // INITIALISE LA SESSION
	session_unset(); // DESACTIVE LA SESSION
	session_destroy(); // DETRUIT LA SESSION
	setcookie('auth', '', time()-1, '/', null, false, true); // DETRUIT LE COOKIE

	header('location: ../espace_commun/accueilCommun.php?accueil=1');
	exit();

?>