<?php 
  // Database
 include('../src/connect.php');
 require_once("mesFonctions.php");
  
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }
  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 12;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  //$articles = $db->query("SELECT * FROM products order by nombre_stock asc LIMIT $paginationStart, $limit")->fetchAll();
  

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="style/miniDesign">
    <link rel="stylesheet" href="style/stylesheet.css" >

    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../design/accueil.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>ARTICLE</title>
    <style>
        .container {
            max-width: 1000px
        }
        .custom-select {
            max-width: 1500px
        }
    </style>
</head>
<body>
<?php logoAdmin(); ?>
    
    
    <div class=" mt-5">
        <h1 class="text-center mb-5">Les articles</h1>
        <!-- Search form -->
        <!--input class="form-control w-25 mx-auto" type="text" placeholder="Search" name="search" aria-label="Search"-->   
        <form method='get' action=''>
            <div class="search-container">
                <input type="text" placeholder="Search.." name="search">
                <button class="bi bi-search"  type="submit" name ="ok"></button>
            </div><hr/>
        </form>
        <form method='get' action=''>
        <button style="width: 5%; padding: 5px; font-size: 0.85em" type="submit" name ="reset">Reset</button>    </form>

    <?php
        /////////////   ALGORITHME DE RECHERCHE /////////////
        if (!isset($_GET['search']) or isset($_POST['reset'])){
            // Get total records
            $sql = $db->query("SELECT count(id) AS id FROM products")->fetchAll();
            $allRecrods = $sql[0]['id'];
            
            // Calculate total pages
            $totoalPages = ceil($allRecrods / $limit);
            // Prev + Next
            $prev = $page - 1;
            $next = $page + 1;

            //Afficher tous les articles
            $res = $db->query('SELECT count(*) from products');
            //$recupArticle = $db->query('SELECT * from products order by nombre_stock asc');
            $recupArticle = $db->query("SELECT * FROM products order by nombre_stock desc LIMIT $paginationStart, $limit")->fetchAll();

            $nbrArticle = $res->fetchColumn();
        }

        if (isset($_GET['search'])){ 
            

            $carac = $_GET['search'];
            $res = $db->query("SELECT count(*) from products where couleur = '$carac' or marque = '$carac' or reference = '$carac' or description = '$carac' or categorie = '$carac' or sous_categorie = '$carac'");
            $nbrArticle = $res->fetchColumn();         

            $recupArticle = $db->prepare("SELECT * from products 
                                        where couleur = ? or marque = ? or reference = ? or description = ? or categorie = ? or sous_categorie = ? 
                                        LIMIT $paginationStart, $limit");
            $recupArticle->execute(array($carac,$carac,$carac,$carac,$carac,$carac));        
            
        
            // Calculate total pages
            $totoalPages = ceil($nbrArticle / $limit);
            // Prev + Next
            $prev = $page - 1;
            $next = $page + 1;
            
            
        } ?>
        
    </div>
        <!-- Select dropdown -->
        <div class="d-flex flex-row-reverse bd-highlight mb-3 mr-5">
            <form action="articles.php" method="post">
                <select name="records-limit" id="records-limit" class="custom-select">
                    <option disabled selected>Limite de lignes</option>
                    <?php foreach([5,7,10,12] as $limit) : ?>
                    <option
                        <?php if(isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $limit) echo 'selected'; ?>
                        value="<?= $limit; ?>">
                        <?= $limit; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <!-- Datatable -->
        <div class="table-responsive  mx-auto w-75 mb-5">
            <table class="table table-bordered table-hover">
                <caption><?= "<b>Il y a " . $nbrArticle . " articles trouvés.</b>"?></caption>
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">Marque</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Sous-categorie</th>
                        <th scope="col">Description</th>
                        <th scope="col">Couleur</th>
                        <th scope="col">Couleur</th>
                        <th scope="col">Prix d'achat HT</th>
                        <th scope="col">Prix de vente HT</th>
                        <th scope="col">Prix TTC</th>
                        <th scope="col">Poids</th>
                        <th scope="col">Référence</th>
                        <th scope="col">Quantité</th>
                        <th scope="col">Remise</th>
                        <th scope="col">Supprimer ?</th>
                        <th scope="col">Modifier ?</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recupArticle as $article){ ?>
                    <tr>
                        <th scope="row"><?= $article['id']; ?></th>
                        <td><?= $article['marque']; ?></td>
                        <td><?= $article['categorie']; ?></td>
                        <td><?= $article['sous_categorie']; ?></td>
                        <td><?= $article['description']; ?></td>
                        <td><?= $article['couleur']; ?></td>
                        <td><?= $article['couleur']; ?></td>
                        <td><?= $article['prix_achat_HT']; ?></td>
                        <td><?= $article['prix_vente_HT']; ?></td>
                        <td><?= $article['priceTTC']; ?></td>
                        <td><?= $article['poids']; ?></td>
                        <td><?= $article['reference']; ?></td>
                        <td><?= $article['nombre_stock']; ?></td>
                        <td><?= $article['remise']; ?></td>
                        <td><a href = "supprimer_Article.php?reference=<?= $article['reference']; ?>" style="color:red; text-decoration: none;"> Supprimer l'article</a></td>
                        <td><a href = "modifier_Article.php?reference=<?= $article['reference']; ?>" style="color:red; text-decoration: none;"> Modifier l'article</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">

                <?php if (!isset($_GET['search']) or isset($_POST['reset'])) { ?>
                    <a class="page-link"
                    href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>">Previous</a>
                    <?php  } ?>

                    <?php if (isset($_GET['search'])) { ?>
                        <a class="page-link"
                        href="<?php if($page <= 1){ echo '#'; } else { echo "?search=".$_GET['search']."&page=" . $prev; } ?>">Previous</a>
                        <?php  } ?>
                </li>
                <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">

                <?php if (!isset($_GET['search']) or isset($_POST['reset'])) { ?>
                    <a class="page-link"
                     <a class="page-link" href="articles.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                     <?php  } ?>


                <?php if (isset($_GET['search'])) { ?>
                    <a class="page-link"
                    <a class="page-link" href="articles.php?search=<?= $_GET['search']; ?>&page=<?= $i; ?>"> <?= $i; ?> </a>
                    <?php  } ?>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
                    
                <?php if (!isset($_GET['search']) or isset($_POST['reset'])) { ?>
                    <a class="page-link"
                    href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?page=". $next; } ?>">Next</a>
                    <?php  } ?>
                    <?php if (isset($_GET['search'])) { ?>
                        <a class="page-link"
                        href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?search=".$_GET['search']."&page=". $next; } ?>">Next</a>
                        <?php  } ?>
                </li>
            </ul>
        </nav>
    </div>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
        });
    </script>
</body>
</html>

