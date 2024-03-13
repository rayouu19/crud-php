<?php
session_start();
require_once '/includes/pdo.php'; // inclure le fichier de connexion à la base de données

if (isset($_POST['boutton-valider'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mdp = $_POST['mdp'];

    // Vérification si le pseudo est vide
    if (empty($pseudo)) {
        $erreur = "Le champ pseudo ne peut pas être vide.";
    } else {
        $erreurs = [];

        if (strlen($mdp) < 8) {
            $erreurs[] = "- Au moins 8 caractères.";
        }
        if (!preg_match('/[A-Z]/', $mdp)) {
            $erreurs[] = "- Au moins une majuscule.";
        }
        if (!preg_match('/[a-z]/', $mdp)) {
            $erreurs[] = "- Au moins une minuscule.";
        }
        if (!preg_match('/[0-9]/', $mdp)) {
            $erreurs[] = "- Au moins un chiffre.";
        }
        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>§]/', $mdp)) {
            $erreurs[] = "- Au moins un caractère spécial.";
        }

        // Utiliser la fonction connectDB() pour établir une connexion à la base de données
        $pdo = connectDB();

        if ($pdo) {
            try {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE pseudo = :pseudo");
                $stmt->execute(array(':pseudo' => $pseudo));
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    $erreurs[] = "- Ce pseudo n'est pas utilisable.";
                }
            } catch (PDOException $e) {
                echo "Erreur de connexion à la base de données: " . $e->getMessage();
            }

            if (!empty($erreurs)) {
                $erreur = "Le mot de passe doit respecter les critères suivants :";
                foreach ($erreurs as $message) {
                    $erreur .= "<br>" . $message;
                }
            } else {
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                // Insertion dans la base de données et redirection
                try {
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mdp, role) VALUES (:pseudo, :mdp, :role)");
                    // Définir le rôle comme 'user'
                    $role = 'user';
                    $stmt->execute(array(':pseudo' => $pseudo, ':mdp' => $mdp_hash, ':role' => $role));
                    
                    // Démarrer une session et rediriger vers la page de bienvenue
                    $_SESSION['pseudo'] = $pseudo;
                    $_SESSION['role'] = $role; // Définir le rôle dans la session
                    header("Location: bienvenue.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Erreur d'inscription: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logobeaup.ico">                      
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/script.js"></script>

</head>
<body>

<section>
    <h1>Inscription</h1>
    <?php
    if(isset($erreur)) {
        echo "<p class='Erreur'>".$erreur."</p>";
    }
    ?>
<form action="" method="POST">
    <label for="pseudo">Pseudo</label>
    <input type="text" id="pseudo" name="pseudo">
    <label for="mdp">Mot de Passe</label>
    <div>
        <input type="password" id="mdpInput" name="mdp">
        <button type="button" class="bouton-afficher" onclick="togglePasswordVisibility()">Afficher</button>
    </div>
    <input type="submit" value="S'inscrire" name="boutton-valider">
    <a href="index.php">Vous avez un compte ?</a>
</form>


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
