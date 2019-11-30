<?php

function getDB(){
    $pdo = new PDO('mysql:host=localhost;dbname=validation', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

$mode = isset($_GET['action'])  && $_GET['action'] == 'edit' ? 11 : 22;


if(isset($_GET['action']) && isset($_GET['id'])){

    if($_GET['action'] == 'suppr'){
        $db = getDB();
        $db->prepare('DELETE FROM matieres WHERE id=?')->execute([$_GET['id']]);
    }elseif ($_GET['action'] == 'edit'){
        $etd = getDB()->query('SELECT * FROM matieres WHERE id='.$_GET['id'])->fetch();
    }

}

if(isset($_POST['nom'])){
    $nom = !empty($_POST['nom']) ? $_POST['nom'] : 'defaut';
    $pdo = getDB();
    if($mode == 22){
        $pdo->prepare('INSERT INTO matieres (nom) VALUE (?)')->execute([$nom]);
    }elseif ($mode == 11){
        $pdo->prepare('UPDATE  matieres SET nom=? WHERE id=?')->execute([$nom, $_GET['id']]);
    }
}
$db = getDB();
$matieres = [];
$req = $db->query('SELECT * FROM matieres');
foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $row){
    $matieres[] = $row;
}
?>


<?php include 'parts/header.php'?>

<div class="container mt-5">
    <h3>Gestion des matieres</h3>
    <hr>
    <div class="row">
        <div class="col-6">
            <form action="" method="post">
                <div class="">
                    <input required value="<?= $mode == 11 ? $etd['nom'] : '' ?>" type="text" class="form-control" name="nom" placeholder="Nom de matiere">
                </div>
                <?php if($mode == 11): ?>
                    <button class="btn btn-sm btn-green" type="submit">Modifier</button>
                    <a href="matiere.php" class="btn btn-sm btn-blue">Créer</a>
                <?php else: ?>
                    <button class="btn btn-sm btn-blue" type="submit">Ajouter</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <h3 class="mt-3">Liste</h3>
    <div class="w-75">
        <table class="table">
            <tr>
                <th>Identifiant</th>
                <th>Nom de la matiere</th>
            </tr>
            <?php foreach ($matieres as $etd): ?>
                <tr>
                    <th><?= $etd['id'] ?></th>
                    <th><?= $etd['nom'] ?></th>
                    <th><a href="matiere.php?action=suppr&id=<?= $etd['id'] ?>"><span class="fa fa-trash red-ic"></span></a></th>
                    <th><a href="matiere.php?action=edit&id=<?= $etd['id'] ?>"><span class="fa fa-edit blue-ic"></span></a></th>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include 'parts/footer.php'?>

