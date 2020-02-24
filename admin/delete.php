<?php require_once 'database.php';
if (!empty($_GET['id'])) {
    $id = verifyInput($_GET['id']);
}

if (!empty($_POST['id'])) {
    $id = verifyInput($_POST['id']);
    $db = Database::connect();
    $statement = $db->prepare('DELETE   FROM items WHERE id=? ');
    $statement->execute(array($id));
    Database::disconnect();
    header('Location:index.php');
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
            <h1><strong>Supprimer un Item </strong> </h1><br>
            <form class="form" role="form" action="delete.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <p class=" alert alert-warning">Etes vous sur de vouloir supprimer ?</p>
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning"> Oui</button>
                    <a href="index.php" class="btn btn-default"> Non</a>
                </div>
            </form>
        </div>
    </div>
</body>