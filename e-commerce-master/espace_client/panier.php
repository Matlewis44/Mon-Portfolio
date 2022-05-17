
<?php
session_start();
require('../src/connect.php');

if(isset($_SESSION["user"])){ 

    if(isset($_GET["action"]))
    {
        if($_GET["action"] == "delete")
        {
            foreach($_SESSION["shopping_cart"] as $keys => $values)
            {
                if($values["item_ref"] == $_GET["reference"])
                {
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>alert("Item Removed")</script>';
                    header("Location:panier.php");
                }
            }
        }
    }
}
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
			<h3>Order Details</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="40%">Référence</th>
						<th width="10%">Quantité</th>
						<th width="20%">Prix</th>
						<th width="20%">Couleur</th>
						<th width="15%">Total</th>
						<th width="5%">Action</th>

					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						$fraisport = 0;
						$totalCharge = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
						<tr>
							<td><?php echo $values["item_ref"]; ?></td>
							<td> <input value=<?php echo $values["item_quantite"]; ?> name="quantity" min='0' max=<?php echo $values["item_dispo"]; ?> type='number' class='quantite'></td>
							<td>$ <?php echo $values["item_prix"]; ?></td>
							<td> <?php echo $values["item_couleur"]; ?></td>
							<td>$ <?php echo number_format($values["item_quantite"] * $values["item_prix"], 2);?></td>
							<td><a href="panier.php?action=delete&reference=<?php echo $values["item_ref"]; ?>"> <span class="text-danger">Remove</span></a></td>
						</tr>
						<?php
							$poids = (int)$values["item_poids"];
							$total = $total + ($values["item_quantite"] * $values["item_prix"]);
							$fraisport = $fraisport + ($poids * 0.02);
							$totalCharge = $total + $fraisport;
							$_SESSION['total'] = $totalCharge;
						}
						?>
						<tr>
							<td colspan="4" align="right">Montant</td>
							<td align="right">$ <?php echo number_format($totalCharge, 2); echo "(" .$total. "+" .$fraisport. ")" ;?></td>
							<td>
								<form action="paiement.php" method="post">
									<input type="hidden" name="name" value="<?php echo $values["item_id"]; ?>">	
									<input type="hidden" name="name" value="<?php echo $values["item_ref"]; ?>">
									<input type="hidden" name="qty" value="<?php echo $values["item_quantite"]; ?>">
									<input type="hidden" name="price" value="<?php echo $values["item_prix"]; ?>">
									<input type="hidden" name="color" value="<?php echo $values["item_couleur"]; ?>">
									<input type="hidden" name="dispo" value="<?php echo $values["item_dispo"]; ?>">
									<input type="hidden" name="total" value="<?php echo $totalCharge ?>">
									<input type="submit" value="Passer commande">
								</form>
							</td>
						</tr>
							</td>
						</tr>
						<?php
					}
					?>
						
				</table>
				<button style="width: 5%; padding: 5px; font-size: 0.85em" onclick="window.location.href='../espace_commun/accueilCommun.php?accueil=1';">Revenir à l'accueil</button>
			</div>
		
	</body>
</html>
