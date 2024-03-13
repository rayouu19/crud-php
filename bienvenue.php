<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logobeaup.ico">
    <title>Bienvenue</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <section>

        <?php
        session_start();

        if(!isset($_SESSION['pseudo'])) {
            header("Location: nonautorise.php"); 
            exit(); 
        }
        
        echo "<h1>Bonjour <br>" .  $_SESSION['pseudo'] . ", vous êtes connecté en tant que ";
        
        // Vérification si 'role' est défini dans la session
        if(isset($_SESSION['role'])) {
            echo ($_SESSION['role'] == 'admin') ? "Admin" : "User";
            if ($_SESSION['role'] == 'admin') {
                echo '<br><br><form action="crud.php" method="GET">';
                echo '<input type="submit" value="Accéder au CRUD">';
                echo '</form>';
            }
        } else {
            echo "Rôle non défini";
        }
        ?>

        <form action="logout.php" method="POST">
            <input type="submit" value="Deconnexion" name="boutton-valider">
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
