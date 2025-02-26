<?php
include 'db.php';

// Récupérer la moyenne des projets
$query = "SELECT nom_projet, ROUND(AVG(note_finale), 2) as moyenne FROM evaluations GROUP BY nom_projet";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats des projets</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Résultats des Projets</h2>
    <table border="1">
        <tr>
            <th>Nom du Projet</th>
            <th>Moyenne sur 20</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nom_projet']); ?></td>
            <td><?php echo htmlspecialchars($row['moyenne']); ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>