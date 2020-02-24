<?php require_once 'database.php';
if (!empty($_GET['id'])) {
    $id = verifyInput($_GET['id']);
}
/**
 * initialise variable to empty string.
 */
$name = '';
$description = '';
$price = '';
$category = '';
$image = '';
$nameError = '';
$descriptionError = '';
$imageError = '';
$categoryError = '';
$priceError = '';
$isSuccess = true;
$isUploadSuccess = false;

if (!empty($_POST)) {
    $name = $_POST['name'];
    $description = verifyInput($_POST['description']);
    $price = verifyInput($_POST['price']);
    $category = verifyInput($_POST['category']);
    $image = verifyInput($_FILES['image']['name']);
    $imagePath = '../images/'.basename($image);
    $imageExtention = pathinfo($imagePath, PATHINFO_EXTENSION);
    $isSuccess = true;
    $isUploadSuccess = false;

    if (empty($name)) {
        $nameError = 'ce champs doit obligatoirement etre remplir ';
        $isSuccess = false;
    }

    if (empty($description)) {
        $descriptionError = 'ce champs doit obligatoirement etre remplir  ';
        $isSuccess = false;
    }

    if (empty($price)) {
        $priceError = 'ce champs doit obligatoirement etre remplir ';
        $isSuccess = false;
    }

    if (empty($category)) {
        $categoryError = 'ce champs doit obligatoirement etre remplir ';
        $isSuccess = false;
    }

    if (empty($image)) {
        $isImageUpdated = false;
    } else {
        $isImageUpdated = true;
        $isUploadSuccess = true;
        if ($imageExtention != 'jpg' && $imageExtention != 'png' && $imageExtention != 'jpeg' && $imageExtention != 'gif') {
            $imageError = ' les fichiers autorises sont jpg, jpeg , png, gif ';
            $isUploadSuccess = false;
        }
        if (file_exists($imagePath)) {
            $imageError = 'le fichier existe deja ';
            $isUploadSuccess = false;
        }
        if ($_FILES['image']['size'] > 500000) {
            $imageError = ' le fichier ne doit pas depasser plus de 500KB ';
            $isUploadSuccess = false;
        }
        if ($isUploadSuccess) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $imageError = "il y'a une erreur lors de l'importation du fichier";
                $isUploadSuccess = false;
            }
        }
    }
    //insert element in the database if  success
    if (($isSuccess && $isUploadSuccess && $isImageUpdated) || ($isSuccess && !$isImageUpdated)) {
        $db = Database::connect();
        if ($isImageUpdated) {
            $statement = $db->prepare('UPDATE   items  SET name=? , description=?, price=? , category=?, image=? WHERE id=?');
            $statement->execute(array($name, $description, $price, $category, $image, $id));
        } else {
            $statement = $db->prepare('UPDATE items SET name= ? , description= ?, price=? , category= ?  WHERE id= ?');
            $statement->execute(array($name, $description, $price, $category, $id));
        }

        Database::disconnect();
        header('Location:index.php');

    // if image updated sucess but image upload failed
    } elseif ($isImageUpdated && !$isUploadSuccess) {
        $db = Database::connect();
        $statement = $db->prepare('SELECT image  FROM items WHERE id=? ');
        $statement->execute(array($id));
        $item = $statement->fetch();
        $image = $item['image'];
        Database::disconnect();
    }
} else {
    $db = Database::connect();
    $statement = $db->prepare('SELECT * FROM items WHERE id=? ');
    $statement->execute(array($id));
    $item = $statement->fetch();
    $name = $item['name'];
    $description = $item['description'];
    $price = $item['price'];
    $category = $item['category'];
    $image = $item['image'];
    Database::disconnect();
}
function verifyInput($var)
{
    $var = trim($var);
    $var = stripcslashes($var);
    $var = htmlspecialchars($var);

    return $var;
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-cutlery"></span> Burger AWS <span class="glyphicon glyphicon-cutlery"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-sm-6">
                <h1><strong>Modifier un item </strong> </h1><br>
                <form class="form" role="form" action="<?php echo 'update.php?id='.$id; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name; ?>">
                        <span class="help-inline"><?php echo $nameError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description :</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                        <span class="help-inline"><?php echo $descriptionError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="price">Prix :</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="prix(en ‎€)" value="<?php echo $price; ?>">
                        <span class="help-inline"><?php echo $priceError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="category">Categories :</label>
                        <select class="form-control" name="category" id="category">
                            <?php
                            $db = Database::connect();
                            foreach ($db->query('SELECT * FROM categories') as $row) {
                                if ($row['id'] == $category) {
                                    echo '<option  selected="selected" value="'.$row['id'].'">'.$row['name'].'</option>';
                                } else {
                                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                }
                            }
                            Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $categoryError; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Image:</label>
                        <p><?php echo $image; ?>
                        </p>
                        <label for="image">Selectionner une image :</label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php echo $imageError; ?></span>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"> Modifier</span></button>
                        <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"> Retour</span></a>
                    </div>
                </form>
            </div>
            <div class=" col-sm-6 site">
                <div class="thumbnail">
                    <img src="<?php echo '../images/'.$image; ?>" alt="...">
                    <div class="price"><?php echo number_format((float) $price, 2, '.', '').'‎€'; ?>
                    </div>
                    <div class="caption">
                        <h4><?php echo $name; ?>
                        </h4>
                        <p><?php echo' '.$description; ?>
                        </p>
                        <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart "></span> Commander</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>