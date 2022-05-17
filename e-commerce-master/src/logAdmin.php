<?php

	if(isset($_COOKIE['auth']) && !isset($_SESSION['connect'])){

		// VARIABLE
		$secret = htmlspecialchars($_COOKIE['auth']);

		// VERIFICATION
		require('../src/connect.php');

		$req = $db->prepare("SELECT count(*) as numberAccount FROM admin WHERE secret = ?");
		$req->execute(array($secret));

		while($user = $req->fetch()){

			if($user['numberAccount'] == 1){

				$reqUser = $db->prepare("SELECT * from admin WHERE secret = ?");
				$reqUser->execute(array($secret));

				while($userAccount = $reqUser->fetch()){

					$_SESSION['connect'] = 1;
					$_SESSION['email']   = $userAccount['email'];

				}

			}

		}

	}

	//ESPACE ADMIN
	if(isset($_SESSION['connect'])){

		require('../src/connect.php');

		$reqUser = $db->prepare("SELECT * from admin WHERE email = ?");
		$reqUser->execute(array($_SESSION['email']));

	}

	
?>