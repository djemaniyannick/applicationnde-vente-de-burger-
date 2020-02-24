<?php require_once 'database.php';
//initialise variable
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
//verify if the post is not empty

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
    //upload image if the format of image is correct
    if (empty($image)) {
        $imageError = 'Ce champ ne peut pas être vide';
        $isSuccess = false;
    } else {
        $isUploadSuccess = true;
        if ($imageExtention != 'jpg' && $imageExtention != 'png' && $imageExtention != 'jpeg' && $imageExtention != 'gif') {
            $imageError = ' le format de fichier est invalide';
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
    if ($isSuccess && $isUploadSuccess) {
        $db = Database::connect();
        $statement = $db->prepare('INSERT INTO items (name , description, price , category, image) VALUES(?,?,?,?,?)');
        $statement->execute(array($name, $description, $price, $category, $image));
        Database::disconnect();
        header('Location:index.php');
    }
}

//function to verify users input to avoid wrong paramater

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
            <h1><strong>Ajouter un item </strong> </h1><br>
            <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
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
                                echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                            }
                            Database::disconnect();
                            ?>
                    </select>
                    <span class="help-inline"><?php echo $categoryError; ?></span>
                </div>
                <div class="form-group">
                    <label for="image">Selectionner une image :</label>
                    <input type="file" id="image" name="image">
                    <span class="help-inline"><?php echo $imageError; ?></span>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"> Ajouter</span></button>
                    <a href="index.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"> Retour</span></a>
                </div>
            </form>
        </div>
    </div>
</body>