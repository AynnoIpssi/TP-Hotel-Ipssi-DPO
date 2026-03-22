<?php 
    include "config.php";

    $chambres = [];

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST["email-client"];
        $room_number = $_POST["room-disponibility"];
        $date_start = $_POST["date-start"];
        $date_end = $_POST["date-end"];

        $stmt_res = $conn->prepare("SELECT * FROM chambre
                                WHERE id NOT IN (
                                SELECT chambre_id FROM reservation_chambre rc
                                JOIN reservation r ON rc.reservation_id = r.id
                                WHERE (r.date_arrivee <= ? AND r.date_depart >= ?)
                                OR (r.date_arrivee <= ? AND r.date_depart >= ?)
                                )"
                            );

        $stmt_res->execute([$date_start, $date_start, $date_end, $date_end]);

        $chambres = $stmt_res->fetchAll(PDO::FETCH_ASSOC);

        // On vérifie si l'email existe
        // prepare() prépare la requête avec ? comme emplacement réservé
        $sql = $conn->prepare("SELECT id FROM client WHERE email = ?");
        // execute() remplace le ? par la valeur de façon sécurisée
        $sql->execute([$email]);
        // fetch() récupère une ligne (comme fetch_assoc() en mysqli)
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if($row) {

            $userId = $row['id'];

            if(strtotime($date_end) < strtotime($date_start)) {
                echo "Erreur : La date de départ ne peut pas être antérieure à la date d'arrivée.";
            }else {

                // On vérifie si la chambre existe
                $sql_check_room = $conn->prepare("SELECT id FROM chambre WHERE numero = ?");
                $sql_check_room->execute([$room_number]);
                $room_row = $sql_check_room->fetch(PDO::FETCH_ASSOC);

                if($room_row) {

                    $room_id = $room_row['id']; 

                    // On vérifie les chevauchements de dates
                    $sql_check_resa = $conn->prepare("
                        SELECT * FROM reservation_chambre rc 
                        JOIN reservation r ON rc.reservation_id = r.id 
                        WHERE rc.chambre_id = ?
                        AND ((r.date_arrivee <= ? AND r.date_depart >= ?)
                        OR (r.date_arrivee <= ? AND r.date_depart >= ?))
                    ");

                    

                    // On passe les 5 valeurs dans l'ordre des ?
                    $sql_check_resa->execute([$room_id, $date_start, $date_start, $date_end, $date_end]);

                    // rowCount() compte les lignes trouvées (équivalent de num_rows en mysqli)
                    if($sql_check_resa->rowCount() > 0) {
                        echo "Erreur : Cette chambre est déjà réservée pour la période choisie.";
                    }else {

                        // On insère la réservation
                        $sql_insert_resa = $conn->prepare("
                            INSERT INTO reservation (client_id, date_arrivee, date_depart) 
                            VALUES (?, ?, ?)
                        ");


                        $sql_insert_resa->execute([$userId, $date_start, $date_end]);
                        $reservation_id = $conn->lastInsertId();

                        // On associe la chambre à la réservation
                        $sql_insert_room = $conn->prepare("
                            INSERT INTO reservation_chambre (reservation_id, chambre_id, client_id) 
                            VALUES (?, ?, ?)
                        ");
                        $sql_insert_room->execute([$reservation_id, $room_id, $userId]);

                        echo "La réservation a été effectuée avec succès !";
                    }

                }else {
                    echo "Chambre non disponible ou inexistante.";
                }
            }
        }else {
            echo "Aucun client trouvé avec cet email.";
        }
        
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Réservation</title>
    <link rel="stylesheet" href="../src/style/index.css">
    <link rel="stylesheet" href="../src//style/reservation_create.css">
    <script src="../src/script/disponibility_room.js" defer></script>
</head>
<body>
    <header>
        <?php include "header.php"; ?>
    </header>
    <nav>
        <?php include "nav.php"; ?>
    </nav>
    <main>
        <h2>Création de Réservation :</h2>
        <form method="POST" action="" id="form-reservation">
            <label for="email-client">Email du client :</label>
            <input type="email" id="email-client" name="email-client">

            <label for="room-disponibility">Numéro de chambre :</label>
            <input type="number" id="room-disponibility" name="room-disponibility">

            <label for="date-start">Date d'arrivée :</label>
            <input type="date" id="date-start" name="date-start" required onchange="verifierDates()">

            <label for="date-end">Date de départ :</label>
            <input type="date" id="date-end" name="date-end" required onchange="verifierDates()">

            <button type="submit">Ajouter la réservation</button>
        </form>

        <h2>Chambre disponible selon tables</h2>

        <table>
            <tr>
                <th>Numéro</th>
                <th>Type</th>
            </tr>
        
            <tbody id="tbody-chambres"></tbody>
        </table>
    </main>
</body>
</html>