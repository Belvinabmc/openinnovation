<?php
include 'db.php';

if (isset($_GET['nom_projet'])) {
    $nom_projet = $_GET['nom_projet'];
    $query = "SELECT cible, thematiques FROM projets WHERE nom_projet = :nom_projet";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nom_projet', $nom_projet);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
}
?>