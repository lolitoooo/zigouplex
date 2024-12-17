<!DOCTYPE html>
<html lang="en">
<?php $title = "Zigouplex - Connexion"; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <h1>Zigouplex : Inscription & Connexion</h1>

    <div class="auth-container">
        <!-- Formulaire d'inscription -->
        <div class="signup-form">
            <h2>Inscription</h2>
            <form action="/auth/register.php" method="POST">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">S'inscrire</button>
            </form>
        </div>

        <!-- Formulaire de connexion -->
        <div class="login-form">
            <h2>Connexion</h2>
            <form action="/auth/login.php" method="POST">
                <label for="login_email">Email :</label>
                <input type="email" id="login_email" name="email" required>

                <label for="login_password">Mot de passe :</label>
                <input type="password" id="login_password" name="password" required>

                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
