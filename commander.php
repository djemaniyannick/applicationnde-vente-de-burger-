<!-- page to show detail about product  -->
<?php
require_once 'admin/database.php';
//verify if id is not empty
if (!empty($_GET['id'])) {
    $id = checkInput($_GET['id']);
}
//call the class connection to make a query
$db = Database::connect();
$statement = $db->prepare('SELECT items.id,items.name, items.description, items.price, items.image, categories.name as category
                        FROM items LEFT JOIN categories ON items.category=categories.id
                        WHERE items.id= ?');
$statement->execute(array($id));
$item = $statement->fetch();
Database::disconnect();

// function that verify the user input security
function checkInput($value)
{
    $value = trim($value);
    $value = htmlspecialchars($value);
    $value = stripslashes($value);

    return $value;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Burger AWS</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger AWS <span class="glyphicon glyphicon-cutlery"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class=" col-sm-6">
                <h1><strong> DETAIL DE COMMANDE </strong> </h1><br>
                <form class="form-group">
                    <div class="form-group">
                        <label>Nom :</label><?php echo' '.$item['name']; ?>
                    </div>
                    <div class="form-group">
                        <label>Description :</label><?php echo' '.$item['description']; ?>
                    </div>
                    <div class="form-group">
                        <label>Prix:</label><?php echo' '.number_format((float) $item['price'], 2, '.', '').'‎€'; ?>
                    </div>
                    <div class="form-group">
                        <label>Categorie:</label><?php echo' '.$item['category']; ?>
                    </div>
                    <div class="form-group">
                        <label>Image:</label><?php echo' '.$item['image']; ?>
                    </div>
                </form>
                <div class="forn-actions">
                    <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"> Retour</span></a>
                </div>
            </div>
            <div class=" col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?php echo 'images/'.$item['image']; ?>" alt="...">
                    <div class="price"><?php echo number_format((float) $item['price'], 2, '.', '').'‎€'; ?>
                    </div>
                    <div class="caption">
                        <h4><?php echo $item['name']; ?>
                        </h4>
                        <p><?php echo' '.$item['description']; ?>
                        </p>
                        <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart "></span> Commander</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>