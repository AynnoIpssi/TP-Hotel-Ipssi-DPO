<?php
$host = "localhost"; // c'est une ip zerotier a changé en localhost pour le prof
$username = "root"; 
$password = ""; 
$dbname = "hotel_reservation"; 

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Affcihage des erreurs

    } catch (PDOException $e) {
    // si la connexion échoue on affiche l'erreur
    die("Échec de la connexion : " . $e->getMessage());
}
?>