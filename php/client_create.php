<?php
include('config.php');

$error = "";
$sucess = "";

// recupération des variables et contraintes 
if (isset($_POST['ok'])) {

    if (
        !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['date_naissance'])
        && !empty($_POST['code_postal']) && !empty($_POST['ville']) && !empty($_POST['adresse'])
        && !empty($_POST['email']) && !empty($_POST['telephone'])
    ) {

        $nom = htmlspecialchars($_POST['nom']); // pour sécuriser
        $prenom = htmlspecialchars($_POST['prenom']);
        $date_naissance = htmlspecialchars($_POST['date_naissance']);
        $code_postal = htmlspecialchars($_POST['code_postal']);
        $ville = htmlspecialchars($_POST['ville']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);
        // pour un mdp on peux utiliser la fonction sha1 pour chiffrer le mdp

        // condition tel
        if (!preg_match("/^[0-9]{10}$/", $telephone)) {   // fonction pregmatch permet de compter une chaine de caractere 
            $error = 'Numéro de téléphone invalide';

        // condition Email
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email invalide";
        }

        //condition code postal
        elseif (!preg_match("/^[0-9]{5}$/", $code_postal)) {
            $error = "Code postal invalide (5 chiffres requis) ";
        }

        //condition adresse 
        elseif (strlen(trim($adresse)) < 5) {
            $error = "Adresse invalide ";
        }

        // insertion en bdd si toute les conditions sont approuvé 
        else {
            
            $insertion = $conn->prepare("INSERT INTO client(nom, prenom, date_naissance, code_postal, ville, adresse, email, telephone) VALUES(?,?,?,?,?,?,?,?)");
            $insertion->execute(array(
                $nom, 
                $prenom, 
                $date_naissance, 
                $code_postal, 
                $ville, 
                $adresse, 
                $email, 
                $telephone
            ));
            $sucess = "Le compte a bien été créé";
        }
    } else {
        $error = "Tous les champs sont obligatoires";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../src/style/index.css">
</head>

<body>
    <header>
        <?php include "header.php" ?>
    </header>
    <nav>
        <?php include "nav.php" ?>
    </nav>
    <main>

        <!-- formulaire -->


        <h2> > Ajout Client < </h2>



                <form method="POST" action="">
                    <label for="nom">Nom : </label>
                    <input type="text" id="nom" name="nom" placeholder="Entrez votre nom..." required>
                    <br />

                    <label for="prenom">Prénom : </label>
                    <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom..." required>
                    <br />

                    <label for="">Date de naissance : </label>
                    <input type="date" id="date_naissance" name="date_naissance" placeholder="Entrez votre date de naissance..." required>
                    <br />

                    <label for="">Code postal : </label>
                    <input type="text" id="code_postal" name="code_postal" placeholder="Indiquez votre code postal..." required>
                    <br />

                    <label for="">Ville : </label>
                    <input type="text" id="ville" name="ville" placeholder="Indiquez votre ville..." required>
                    <br />

                    <label for="">Adresse : : </label>
                    <input type="text" id="adresse" name="adresse" placeholder="Entrez votre adresse ..." required>
                    <br />

                    <label for="">Email : </label>
                    <input type="text" id="email" name="email" placeholder="Entrez votre email..." required>
                    <br />

                    <label for="">telephone : </label>
                    <input type="text" id="telephone" name="telephone" placeholder="Indiquez votre numéro de téléphone..." required>
                    <br />

                    <input type="submit" value="Inscrire" name="ok">

                    <?php if (!empty($error)) { ?>
                        <p style="color:red;"><?php echo $error; ?></p>
                    <?php } ?>

                    <?php if (!empty($success)) { ?>
                        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
                    <?php } ?>

                </form>

    </main>
</body>

</html>