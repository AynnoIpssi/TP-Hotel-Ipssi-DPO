<?php
include "config.php";

$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];

$stmt = $conn->prepare("SELECT * FROM chambre
    WHERE id NOT IN (
        SELECT chambre_id FROM reservation_chambre rc
        JOIN reservation r ON rc.reservation_id = r.id
        WHERE (r.date_arrivee <= ? AND r.date_depart >= ?)
        OR (r.date_arrivee <= ? AND r.date_depart >= ?)
    )
");
$stmt->execute([$date_start, $date_start, $date_end, $date_end]);
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// On retourne les chambres en JSON
echo json_encode($chambres);
?>