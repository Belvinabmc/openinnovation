<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = "sql307.infinityfree.com"; // Remplace par ton hôte MySQL
$dbname = "if0_38256733_openinnovation"; // Nom exact de ta base de données
$username = "if0_38256733"; // Ton nom d'utilisateur MySQL
$password = "bassouaka"; // Ton mot de passe MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    die("? Erreur de connexion : " . $e->getMessage());
}

// Récupérer la liste des projets existants dans la table evaluations
try {
    $query = "SELECT DISTINCT nom_projet FROM evaluations LIMIT 25";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("? Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Innovation - EPSI</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="container">
        <h2>Open Innovation</h2>

        <form action="traitement.php" method="POST">
            <label>Nom & Prénom du jury :</label>
            <input type="text" name="jury" required>

            <label>Date :</label>
            <input type="date" name="date" required>

            <label>Choisir un projet existant ou en créer un nouveau :</label>
            <select name="nom_projet_exist" id="nom_projet_exist" onchange="toggleNewProjectInput()">
                <option value="">-- Sélectionner un projet existant --</option>
                <?php
                foreach ($projets as $row) {
                    echo "<option value='" . htmlspecialchars($row['nom_projet']) . "'>" . htmlspecialchars($row['nom_projet']) . "</option>";
                }
                ?>
                <option value="new">-- Ajouter un nouveau projet --</option>
            </select>

            <div id="new_project_container" style="display: none;">
                <label>Nom du nouveau projet :</label>
                <input type="text" name="nom_projet">
            </div>

            <label>Cible :</label>
            <input type="text" name="cible" id="cible" required>

            <label>Thématique :</label>
            <input type="text" name="thematique" id="thematique" required>

            <label>Créativité / Originalité :</label>
            <select name="creativite">
                <?php for($i = 0; $i <= 5; $i += 0.25) { echo "<option value='$i'>$i</option>"; } ?>
            </select>

            <label>Technicité :</label>
            <select name="technicite">
                <?php for($i = 0; $i <= 5; $i += 0.25) { echo "<option value='$i'>$i</option>"; } ?>
            </select>

            <label>Design :</label>
            <select name="design">
                <?php for($i = 0; $i <= 5; $i += 0.25) { echo "<option value='$i'>$i</option>"; } ?>
            </select>

            <label>Présentation orale :</label>
            <select name="presentation">
                <?php for($i = 0; $i <= 5; $i += 0.25) { echo "<option value='$i'>$i</option>"; } ?>
            </select>

            <label>Aboutissement à la solution :</label>
            <select name="aboutissement">
                <?php for($i = 0; $i <= 5; $i += 0.25) { echo "<option value='$i'>$i</option>"; } ?>
            </select>

            <label>Niveau d'innovation :</label>
            <select name="niveau_innovation">
                <option value="pas_du_tout_innovant">Pas du tout innovant</option>
                <option value="un_peu_innovant">Un peu innovant</option>
                <option value="assez_innovant">Assez innovant</option>
                <option value="tres_innovant">Très innovant</option>
            </select>

            <label>Commentaires & Suggestions :</label>
            <textarea name="commentaire"></textarea>

            <button type="submit">Soumettre</button>
        </form>
    </div>

    <script>
        function toggleNewProjectInput() {
            var select = document.getElementById("nom_projet_exist");
            var newProjectContainer = document.getElementById("new_project_container");

            if (select.value === "new") {
                newProjectContainer.style.display = "block";
            } else {
                newProjectContainer.style.display = "none";
            }
        }

        document.getElementById("nom_projet_exist").addEventListener("change", function() {
            var projet = this.value;

            if (projet !== "" && projet !== "new") {
                fetch("get_project_data.php?nom_projet=" + encodeURIComponent(projet))
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("cible").value = data.cible || "";
                        document.getElementById("thematique").value = data.thematique || "";
                    })
                    .catch(error => console.error("Erreur de récupération des données:", error));
            } else {
                document.getElementById("cible").value = "";
                document.getElementById("thematique").value = "";
            }
        });
    </script>

</body>
</html>