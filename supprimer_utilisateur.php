<?php
// Vérifier si le formulaire de suppression a été soumis
if(isset($_POST['supprimer'])) {
    // Vérifier si le champ du pseudo est renseigné
    if(isset($_POST['pseudo'])) {
        $pseudo = $_POST['pseudo'];

        // Inclure le fichier de connexion à la base de données
        require_once 'includes/pdo.php';

        try {
            // Connexion à la base de données en utilisant la fonction connectDB()
            $pdo = connectDB();

            // Supprimer l'utilisateur de la base de données en utilisant le pseudo
            $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE pseudo = :pseudo");
            $stmt->execute(array(':pseudo' => $pseudo));

            // Redirection vers la page CRUD après suppression
            header("Location: crud.php");
            exit();
        } catch (PDOException $e) {
            $erreur = "Erreur de suppression de l'utilisateur: " . $e->getMessage();
        }
    }
} else {
    // Redirection vers la page CRUD si le formulaire n'a pas été soumis correctement
    header("Location: crud.php");
    exit();
}
?>
