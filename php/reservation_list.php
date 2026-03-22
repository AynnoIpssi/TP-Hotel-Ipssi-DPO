<?php
include "config.php";

// On vérifie si supprimer est dans l'url
if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $stmt_delete = $conn->prepare("DELETE FROM reservation WHERE id = ?");
    $stmt_delete->execute([$id]);
}

// On récupère les valeurs de reservation et on fait les liaisons
$stmt_res = $conn->prepare("SELECT *, reservation.id AS reservation_id
                            FROM reservation
                            JOIN reservation_chambre ON reservation_chambre.reservation_id = reservation.id
                            JOIN chambre ON chambre.id = reservation_chambre.chambre_id
                            JOIN client ON client.id = reservation.client_id");
$stmt_res->execute();
$reservations = $stmt_res->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/style/index.css">
    <link rel="stylesheet" href="../src/style/reservation_list.css">
    <script src="../src/script/confirm_delete.js" defer></script>
</head>
<body>
    <header>
        <?php include "header.php" ?>
    </header>
    <nav>
        <?php include "nav.php" ?>
    </nav>
    <main>
        <table>
            <tr>
                <th>Email du client:</th>
                <th>Numéro de chambre :</th>
                <th>Date de début :</th>
                <th>Date de fin :</th>
                <th>Date d'enregistrement :</th>
                <th>Supprimer ?</th>
            </tr>
        
            <!-- on fait une boucle foreach car on a récupéré tout d'un coup avec fetchAll -->
            <?php foreach ($reservations as $row) { ?>
                <tr>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['numero'] ?></td>
                    <td><?php echo $row['date_arrivee'] ?></td>
                    <td><?php echo $row['date_depart'] ?></td>
                    <td><?php echo $row['created_at'] ?></td>
                    <td><a onclick="confirmerSuppression(<?php echo $row['reservation_id']; ?>)">❌</a></td>
                </tr>
            <?php } ?>
        </table>
    </main>
</body>
</html>