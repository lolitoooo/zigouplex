<?php 
include './core/init.php'; 
include 'mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    sendMail($email, $message);
}

?>
<!DOCTYPE html>
<html lang="fr">
<?php $title = "Zigouplex - Contact"; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <main>
        <section class="contact-section">
            <h1>Contactez Zigouplex</h1>
            <p>Si vous avez des questions ou des demandes, n'hésitez pas à nous contacter via le formulaire ci-dessous ou à nous joindre directement par téléphone ou email.</p>
            
            <div class="contact-info">
                <h2>Informations de contact</h2>
                <ul>
                    <li><strong>Adresse :</strong> 123 Rue de l'Innovation, 75000 Paris, France</li>
                    <li><strong>Téléphone :</strong> +33 1 23 45 67 89</li>
                    <li><strong>Email :</strong> admin@zigouplex.com</li>
                </ul>
            </div>

            <div class="contact-form">
                <h2>Envoyer un message</h2>
                <form action="" method="post">
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" required placeholder="Votre nom">

                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required placeholder="Votre email">

                    <label for="message">Message :</label>
                    <textarea id="message" name="message" rows="5" required placeholder="Votre message"></textarea>

                    <button type="submit" class="btn-primary">Envoyer</button>
                </form>
            </div>
        </section>
    </main>
    <?php include './include/footer.php'; ?>
</body>
</html>