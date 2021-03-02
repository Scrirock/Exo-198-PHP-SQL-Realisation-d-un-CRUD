<?php
require "./Classe/DB.php";
$conn = DB::getInstance();

function sanitize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);

    return $data;
}

function create($lastname, $firstname, $age){
    $conn = DB::getInstance();
    $add = $conn->prepare("INSERT INTO crud.eleves (nom, prenom, age) 
                                         VALUES (:lastname, :firstname, :age)");

    $add->bindParam(":lastname", $lastname);
    $add->bindParam(":firstname", $firstname);
    $add->bindParam(":age", $age);

    $add->execute();
}

function read(): array{
    $conn = DB::getInstance();
    $select = $conn->prepare("SELECT * FROM crud.eleves");
    $select->execute();
    return $select->fetchAll();
}

function update($id, $lastname, $firstname, $age){
    $conn = DB::getInstance();
    $update = $conn->prepare("UPDATE crud.eleves SET nom = :lastname, prenom = :firstname, age = :age WHERE id = :id");
    $update->bindParam(":lastname", $lastname);
    $update->bindParam(":firstname", $firstname);
    $update->bindParam(":age", $age);
    $update->bindParam(":id", $id);
    $update->execute();
}

function delete($id){
    $conn = DB::getInstance();
    $delete = $conn->prepare("DELETE FROM crud.eleves WHERE id = :id");
    $delete->bindParam(":id", $id);
    $delete->execute();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>CRUD eleves</title>
</head>
<body>

    <h1>CRUD élèves</h1>
    <div id="input">
        <div id="add">
            <p>Ajouter un élève</p>
            <form action="index.php" method="POST">
                <label for="lastname">Nom: </label>
                <input type="text" id="lastname" name="lastname">
                <label for="firstname">Prénom: </label>
                <input type="text" id="firstname" name="firstname">
                <label for="age">Age: </label>
                <input type="text" id="age" name="age">

                <input type="submit" value="Ajouter">
            </form>
        </div>
        <div id="update">
            <p>Modifier un élèves</p>
            <form action="index.php" method="POST">
                <label for="lastname2">Nom: </label>
                <input type="text" id="lastname2" name="lastname2">
                <label for="firstname2">Prénom: </label>
                <input type="text" id="firstname2" name="firstname2">
                <label for="age2">Age: </label>
                <input type="text" id="age2" name="age2"><br>
                <label for="id">Id: </label>
                <input type="text" id="id" name="id">

                <input type="submit" value="Update">
            </form>
        </div>
        <div id="delete">
            <p>Supprimmer un elève</p>
            <form action="index.php" method="POST">
                <label for="id2">Id: </label>
                <input type="text" id="id2" name="id2">

                <input type="submit">
            </form>
        </div>
    </div>
    <?php
        if (isset($_POST["lastname"], $_POST["firstname"], $_POST["age"])){
            $lastname = sanitize($_POST["lastname"]);
            $firstname = sanitize($_POST["firstname"]);
            $age = sanitize($_POST["age"]);

            create($lastname, $firstname, $age);
        }

        if (isset($_POST["lastname2"], $_POST["firstname2"], $_POST["age2"], $_POST["id"])){
            $lastname2 = sanitize($_POST["lastname2"]);
            $firstname2 = sanitize($_POST["firstname2"]);
            $age2 = sanitize($_POST["age2"]);
            $id = sanitize($_POST["id"]);

            update($id, $lastname2, $firstname2, $age2);
        }
    if (isset($_POST["id2"])){
        $id2 = sanitize($_POST["id2"]);

        delete($id2);
    }
    ?>

    <div id="show">
        <div>
            <a href="index.php?read">Afficher les eleves</a>
            <a href="index.php">Masquer les eleves</a>
        </div>
        <?php
            if (isset($_GET["read"])){
                $read = read();
                echo "<div id='eleves'>";
                foreach ($read as $eleve){
                    echo "<div class='border'>";
                    echo "<p><span>Id: </span>".$eleve["id"]."</p>";
                    echo "<p><span>Nom: </span>".$eleve["nom"]."</p>";
                    echo "<p><span>Prénom: </span>".$eleve["prenom"]."</p>";
                    echo "<p><span>Age: </span>".$eleve["age"]."</p>";
                    echo "</div>";
                }
                echo "</div>";
            }
        ?>
    </div>



</body>
</html>