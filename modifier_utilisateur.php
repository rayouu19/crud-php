<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: nonautorise.php");
    exit();
}

// Vérifier le rôle de l'utilisateur
if ($_SESSION['role'] !== 'admin') {
    header("Location: nonautorise.php");
    exit();
}

require_once '/includes/pdo.php'; // inclure le fichier de connexion à la base de données

$error = "";

if (isset($_POST['pseudo'])) {
    $pseudo = $_POST['pseudo'];

    try {
        $pdo = connectDB(); // Utiliser la fonction connectDB() pour établir une connexion à la base de données
        
        // Préparer la requête pour récupérer les données de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
        $stmt->execute(array(':pseudo' => $pseudo));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "Utilisateur non trouvé.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        exit();
    }
} else {
    echo "Aucun utilisateur spécifié.";
    exit();
}

if (isset($_POST['modifier'])) {
    // Vérifier si les indices existent dans le tableau $_POST
    if (isset($_POST['nouveau_pseudo'], $_POST['nouveau_mdp'])) {
        $nouveau_pseudo = $_POST['nouveau_pseudo'];
        $nouveau_mdp = $_POST['nouveau_mdp'];

        // Autoriser la modification du rôle uniquement si l'utilisateur actuel n'est pas le même que celui modifié
        $role = ($_SESSION['pseudo'] !== $pseudo && isset($_POST['role'])) ? $_POST['role'] : $user['role'];

        try {
            // Commencer une transaction
            $pdo->beginTransaction();

            // Préparer et exécuter la requête SQL pour mettre à jour les données
            $query = "UPDATE utilisateurs SET ";
            $params = array();

            if (!empty($nouveau_pseudo)) {
                $query .= "pseudo = :nouveau_pseudo, ";
                $params[':nouveau_pseudo'] = $nouveau_pseudo;
            }

            if (!empty($nouveau_mdp)) {
                $query .= "mdp = :nouveau_mdp, ";
                $params[':nouveau_mdp'] = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
            }

            $query .= "role = :role WHERE pseudo = :pseudo";
            $params[':role'] = $role;
            $params[':pseudo'] = $pseudo;

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Valider la transaction
            $pdo->commit();

            // Redirection vers la page de liste des utilisateurs après la mise à jour
            header("Location: crud.php");
            exit();
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $pdo->rollBack();
            $error = "Erreur de mise à jour: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - Edit User</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="icon" href="logobeaup.ico">     
</head>
<body>

<section>
    
    <h1>Modifier l'utilisateur</h1>
    <?php if(!empty($error)): ?>
        <p class="Error"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>Role: <?php echo htmlspecialchars($user['role']); ?></p>  <!-- Utilisez htmlspecialchars pour échapper les caractères spéciaux -->
    <p>Pseudo: <?php echo htmlspecialchars($user['pseudo']); ?><br><br></p>  <!-- Utilisez htmlspecialchars pour échapper les caractères spéciaux -->

    <form action="" method="post">
        <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($pseudo); ?>">  <!-- Utilisez htmlspecialchars pour échapper les caractères spéciaux -->
        <label for="nouveau_pseudo">New Pseudo</label>
        <input type="text" id="nouveau_pseudo" name="nouveau_pseudo">
        <label for="nouveau_mdp">New Password</label>
        <input type="password" id="nouveau_mdp" name="nouveau_mdp">
        <?php if ($_SESSION['pseudo'] !== $pseudo): ?>
            <fieldset>
                <legend>&nbsp Role &nbsp</legend>
                <!-- Afficher le champ de saisie du rôle uniquement si l'utilisateur n'essaie pas de modifier son propre compte -->
                <input type="radio" id="user" name="role" value="user" <?php if ($user['role'] == 'user') echo 'checked'; ?>>
                <label for="user">User</label><br>
                <input type="radio" id="admin" name="role" value="admin" <?php if ($user['role'] == 'admin') echo 'checked'; ?>>
                <label for="admin">Admin</label>
            </fieldset>
        <?php endif; ?>

        <input type="submit" name="modifier" value="Modifier">
    </form>
    <a href="crud.php">Retourner à la liste</a>

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

</section>

</body>
</html>
