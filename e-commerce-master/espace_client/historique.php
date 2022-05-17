<?php
session_start();
require('../src/connect.php');

foreach ($_POST as $k => $v) $k = $v;
$requser = $db->prepare("SELECT * FROM membres WHERE email = ?");
$requser->execute(array($_SESSION['user']));
$user = $requser->fetch();

$requser2 = $db->prepare("SELECT num_facture,date,prix FROM factures WHERE id_client = ? group by num_facture");
$requser2->execute(array($user["id_client"]));
$factures = $requser2->fetchAll();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Mon panier</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>
	<body>
		<header>
		</header>
			<div style="clear:both"></div>
			<br />
			<h3>Historique de commandes</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="25%">Numéro de facture</th>
						<th width="20%">Date</th>   
						<th width="15%">Prix</th>
						<th width="5%">Consulter</th>
						<th width="5%">PDF</th>
					</tr>
                    <?php foreach($factures as $keys => $values) 
                    {
                        ?>
                    <tr>
                        <td><?= $values["num_facture"];?></td>
                        <td><?= $values["date"];?></td>
                        <td><?= $values["prix"];?> €</td>
                        <td><a href="consultationCommande.php?num_facture=<?php echo $values["num_facture"]; ?>"> <span class="text-danger">Consulter</span></a></td>
						<td><a href="pdf.php?num_facture=<?php echo $values["num_facture"]; ?>"> <span class="text-danger">PDF</span></a></td>
                    </tr>
                    <?php } ?>
                </table>
				<button style="width: 5%; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_commun/accueilCommun.php?accueil=1';">Revenir à l'accueil</button>
    </body>
</html>