<?php
session_start();
require_once '/includes/pdo.php'; // inclure le fichier de connexion à la base de données

if(isset($_POST['boutton-valider'])) {
    
    if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
        
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        $erreur = "";

        try {
            $pdo = connectDB(); // Utiliser la fonction de connexion à la base de données

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
            $stmt->execute(array(':pseudo' => $pseudo));
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if($user && password_verify($mdp, $user['mdp'])) {
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['pseudo'] = $pseudo;
                $_SESSION['role'] = $user['role']; // Stocker le rôle de l'utilisateur
                
                // Rediriger vers la page de bienvenue
                header("Location: bienvenue.php");
                exit(); 
            } else {
                $erreur = "Pseudo ou Mot de passe incorrects !";
            }
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logobeaup.ico">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/script.js"></script> <!-- Ajout de la balise script pour inclure le fichier JavaScript externe -->
</head>
<body>

   <section>
       <h1>Connexion</h1>
       <?php 
       if(isset($erreur)) {
           echo "<p class='Erreur'>".$erreur."</p>";
       }
       ?>
       <form action="" method="POST">
           <label>Pseudo</label>
           <input type="text" name="pseudo">
           <label class="mdp-label">Mot de Passe</label>
           <div>
               <input type="password" name="mdp" id="mdpInput">
               <button type="button" class="bouton-afficher" onclick="togglePasswordVisibility()">Afficher</button>
           </div>
           <input type="submit" value="Valider" name="boutton-valider">
       </form>
       <a href="inscription.php">S'inscrire ?</a>

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
