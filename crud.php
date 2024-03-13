<?php
session_start();
require_once '/includes/pdo.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: nonautorise.php");
    exit();
}

$pdo = connectDB();

$erreur = "";

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM utilisateurs");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $erreur = "Erreur de récupération des utilisateurs: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD - Utilisateurs</title>
    <link rel="icon" href="logobeaup.ico">     
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<section>
    <h1>Liste des utilisateurs</h1>
    <?php if(!empty($erreur)): ?>
        <p class="Erreur"><?php echo $erreur; ?></p>
    <?php endif; ?>
    <table>
        <tr>
            <th>Pseudo</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['pseudo']); ?></td>
                <td>
                    <form action="modifier_utilisateur.php" method="post" style="display: inline;">
                        <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($user['pseudo']); ?>">
                        <input type="submit" name="modifier" value=" Modifier ">
                    </form>
                    <?php if ($_SESSION['pseudo'] !== $user['pseudo']): ?>
                        <form action="supprimer_utilisateur.php" method="post" style="display: inline;">
                            <input type="hidden" name="pseudo" value="<?php echo htmlspecialchars($user['pseudo']); ?>">
                            <input type="submit" name="supprimer" value=" Supprimer ">
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <form action="ajouter_utilisateur.php" method="post">
        <input type="submit" name="ajouter" value="Ajouter Utilisateur">
    </form>
    <a href="bienvenue.php">Accueil</a>

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