<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Connexion � la base de donn�es

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Erreur : Le formulaire n'a pas �t� soumis via POST.");
}

// R�cup�ration et s�curisation des donn�es
$jury = htmlspecialchars(trim($_POST['jury'] ?? ""), ENT_QUOTES, 'UTF-8');
$date_eval = htmlspecialchars(trim($_POST['date'] ?? ""), ENT_QUOTES, 'UTF-8');

// V�rifier si c'est un projet existant ou un nouveau
$nom_projet_exist = $_POST['nom_projet_exist'] ?? "";
$nom_projet_new = $_POST['nom_projet'] ?? "";

if (!empty($nom_projet_new)) {
    $nom_projet = htmlspecialchars(trim($nom_projet_new), ENT_QUOTES, 'UTF-8');
} elseif (!empty($nom_projet_exist)) {
    $nom_projet = htmlspecialchars(trim($nom_projet_exist), ENT_QUOTES, 'UTF-8');
} else {
    die("Erreur : Vous devez renseigner un projet (existant ou nouveau).");
}

$cible = htmlspecialchars(trim($_POST['cible'] ?? ""), ENT_QUOTES, 'UTF-8');
$thematique = htmlspecialchars(trim($_POST['thematique'] ?? ""), ENT_QUOTES, 'UTF-8');

// R�cup�ration des autres donn�es avec validation des nombres
$creativite = floatval($_POST['creativite'] ?? 0);
$technicite = floatval($_POST['technicite'] ?? 0);
$design = floatval($_POST['design'] ?? 0);
$presentation = floatval($_POST['presentation'] ?? 0);
$aboutissement = floatval($_POST['aboutissement'] ?? 0);
$niveau_innovation = htmlspecialchars(trim($_POST['niveau_innovation'] ?? ""), ENT_QUOTES, 'UTF-8');
$commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ""), ENT_QUOTES, 'UTF-8');

// Calcul de la note finale
$note_finale = (($creativite + $technicite + $design + $presentation + $aboutissement) / 5) * 4;

try {
    // V�rifier si le projet existe d�j�
    $stmt = $conn->prepare("SELECT COUNT(*) FROM projets WHERE nom_projet = ?");
    $stmt->execute([$nom_projet]);
    $projectExists = $stmt->fetchColumn();

    if ($projectExists == 0) {
        // Ins�rer le projet si nouveau
        $stmt = $conn->prepare("INSERT INTO projets (nom_projet, moyenne_finale) VALUES (?, ?)");
        $stmt->execute([$nom_projet, $note_finale]);
    }

    // Ins�rer l'�valuation
    $stmt = $conn->prepare("INSERT INTO evaluations 
        (jury, date_eval, nom_projet, cible, thematique, creativite, technicite, design, presentation, aboutissement, niveau_innovation, commentaire, note_finale) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([$jury, $date_eval, $nom_projet, $cible, $thematique, $creativite, $technicite, $design, $presentation, $aboutissement, $niveau_innovation, $commentaire, $note_finale]);

    // Mettre � jour la moyenne_finale dans projets
    $stmt = $conn->prepare("UPDATE projets 
        SET moyenne_finale = (SELECT AVG(note_finale) FROM evaluations WHERE evaluations.nom_projet = projets.nom_projet) 
        WHERE projets.nom_projet = ?");
    $stmt->execute([$nom_projet]);

    // Redirection apr�s soumission
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
