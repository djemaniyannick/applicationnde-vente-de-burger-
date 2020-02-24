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
            <h1><strong>Liste des Items</strong>
                <a href="insert.php" class="btn btn-success btn-lg"><span class=" glyphicon glyphicon-plus"></span>Ajouter</a>
            </h1>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Description</th>
                        <th scope="col">Prix</th>
                        <th scope="col">categories</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                require_once 'database.php';
                $db = Database::connect();
                $statement = $db->query('SELECT items.id,items.name, items.description, items.price, categories.name as category
                                        FROM items LEFT JOIN categories ON items.category=categories.id
                                        ORDER BY items.id DESC');
                while ($item = $statement->fetch()) {
                    echo' <tr>';
                    echo' <td>'.$item['name'].'</td>';
                    echo' <td>'.$item['description'].'</td>';
                    echo' <td>'.number_format((float) $item['price'], 2, '.', '').'</td>';
                    echo' <td>'.$item['category'].'</td>';
                    echo' <td width=300>';
                    echo' <a class="btn btn-default" href="view.php?id='.$item['id'].'" ><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
                    echo' <a href="update.php?id='.$item['id'].'" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                    echo' <a href="delete.php?id='.$item['id'].'" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
                    echo' </td>';
                    echo' </tr>';
                }
                Database::disconnect();
                ?>
                </tbody>
            </table>
        </div>
    </div>

</body>