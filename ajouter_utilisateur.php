<?php
// Inclure le fichier de connexion à la base de données
require_once 'includes/pdo.php';

// Récupérer tous les utilisateurs
try {
    $pdo = connectDB(); 
    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erreur = "Erreur de récupération des utilisateurs: " . $e->getMessage();
}

$erreur = '';

// Vérification si le formulaire d'ajout a été soumis
if(isset($_POST['ajouter'])) {
    // Vérification si les champs sont renseignés et non vides
    if(!empty($_POST['pseudo']) && !empty($_POST['mdp'])) {
        $nouveau_pseudo = $_POST['pseudo'];
        $nouveau_mdp = $_POST['mdp'];

        try {
            // Vérification si le nouveau pseudo est déjà utilisé par un autre utilisateur
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE pseudo = :pseudo");
            $stmt->execute(array(':pseudo' => $nouveau_pseudo));
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $erreur = "Ce pseudo est déjà utilisé par un autre utilisateur.";
            } else {
                // Ajouter le nouvel utilisateur à la base de données
                $mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mdp) VALUES (:pseudo, :mdp)");
                $stmt->execute(array(':pseudo' => $nouveau_pseudo, ':mdp' => $mdp_hash));

                header("Location: crud.php");
                exit();
            }
        } catch (PDOException $e) {
            $erreur = "Erreur d'ajout d'utilisateur: " . $e->getMessage();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Utilisateur</title>
    <link rel="icon" href="logobeaup.ico">     
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<div class="bubbles">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
</div>

<section>
    <h1>Ajouter Utilisateur</h1>
    <?php if(isset($erreur)): ?>
        <p class="Erreur"><?php echo $erreur; ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label>Pseudo</label>
        <input type="text" name="pseudo">
        <label>Mot de passe</label>
        <input type="password" name="mdp">
        <input type="submit" name="ajouter" value="Ajouter">
    </form>
</section>

</body>
</html>
