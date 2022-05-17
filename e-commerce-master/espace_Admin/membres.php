<?php 
  // Database
 include('../src/connect.php');
 require_once("mesFonctions.php");
  
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }
  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 5;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;

  


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
    <title>membre</title>
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
        <h1 class="text-center mb-5">Les clients inscrits</h1>
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
            $sql = $db->query("SELECT count(id) AS id FROM membres")->fetchAll();
            $allRecrods = $sql[0]['id'];

              // Calculate total pages
            $totoalPages = ceil($allRecrods / $limit);
            // Prev + Next
            $prev = $page - 1;
            $next = $page + 1;

            //Afficher tous les users
            $res = $db->query('SELECT count(*) from membres');
            //$recupUsers = $db->query('SELECT * from membres order by nombre_stock asc');
            $recupUsers = $db->query("SELECT * FROM membres LIMIT $paginationStart, $limit")->fetchAll();

            $nbrClients = $res->fetchColumn();
        }

        if (isset($_GET['search'])){ 
            $carac = $_GET['search'];
            $res = $db->query("SELECT count(*) from membres where civilité = '$carac' or prenom = '$carac' or nom = '$carac' or email = '$carac' or adresse_livraison = '$carac' or pays='$carac' or ville='$carac'");
            $nbrClients = $res->fetchColumn();       

            $recupUsers = $db->prepare("SELECT * from membres 
                                        where civilité = ? or prenom = ? or nom = ? or email = ? or adresse_livraison = ? or pays=? or ville=?
                                        LIMIT $paginationStart, $limit");
            $recupUsers->execute(array($carac,$carac,$carac, $carac,$carac,$carac, $carac));        
            
            // Calculate total pages
            $totoalPages = ceil($nbrClients / $limit);
            // Prev + Next
            $prev = $page - 1;
            $next = $page + 1;
            
        } ?>
        
    </div>
        <!-- Select dropdown -->
        <div class="d-flex flex-row-reverse bd-highlight mb-3 mr-5">
            <form action="membres.php" method="post">
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
                <caption><?= "<b>Il y a " . $nbrClients . " clients trouvés.</b>"?></caption>
                <thead>
                <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">Civilité</th>
                        <th scope="col">Prenom</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Adresse mail</th>
                        <th scope="col">Pays</th>
                        <th scope="col">Adresse de livraison</th>
                        <th scope="col">Ville</th>    
                        <th scope="col">Bannir ?</th>                                
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recupUsers as $user){ ?>
                    <tr>
                        <th scope="row"><?php echo $user['id_client']; ?></th>
                        <td><?php echo $user['civilité']; ?></td>
                        <td><?php echo $user['prenom']; ?></td>
                        <td><?php echo $user['nom']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['pays']; ?></td>
                        <td><?php echo $user['adresse_livraison']; ?></td>
                        <td><?php echo $user['ville']; ?></td>
                        <td><a href = "bannir.php?email=<?= $user['email']; ?>" style="color:red; text-decoration: none;"> Bannir le membre</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination justify-content-center ">

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
                     <a class="page-link" href="membres.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                     <?php  } ?>

                     
                <?php if (isset($_GET['search'])) { ?>
                    <a class="page-link"
                    <a class="page-link" href="membres.php?search=<?= $_GET['search']; ?>&page=<?= $i; ?>"> <?= $i; ?> </a>
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
