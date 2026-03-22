<?php
    include('config.php');

    // recupération des clients 
    $requete = $conn->prepare("SELECT * FROM client ORDER BY nom ASC");
    $requete->execute();

    // stockage des clients
    $clients =$requete->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des clients</title>
    <link rel="stylesheet" href="../src/style/index.css">
    <link rel="stylesheet" href="../src/style/client_list.css">
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
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Date de naissance</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>telephone</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['Prenom']) ?></td>
                    <td><?= htmlspecialchars($client['Date de naissance']) ?></td>
                    <td><?= htmlspecialchars($client['Code postal']) ?></td>
                    <td><?= htmlspecialchars($client['Ville']) ?></td>
                    <td><?= htmlspecialchars($client['Adresse']) ?></td>
                    <td><?= htmlspecialchars($client['Email']) ?></td>
                    <td><?= htmlspecialchars($client['telephone']) ?></td>
                    <td class="img"><a href="modifyclient.php?id="><img src="../src/img/write.png" alt=""></a></td>
                    <td class="img"><a href="deleteclient.php?id="><img src="../src/img/remove.png" alt=""></a></td>
                </tr>
            </tbody>

        </table>
    </main>
</body>
</html>
