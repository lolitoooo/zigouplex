<?php
include '../core/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars(password_hash($_POST['password'], PASSWORD_BCRYPT));

    if($password == "" || $email == "" || $username == "") {
        echo "Veuillez remplir tous les champs.";
        header("Location: /register.php");
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        header("Location: /register.php");
        return;
    }

    if (strlen($username) < 3) {
        echo "Nom d'utilisateur trop court.";
        header("Location: /register.php");
        return;
    }

    if (strlen($_POST['password']) < 8) {
        echo "Mot de passe doit contenir au moins 8 caractères.";
        header("Location: /register.php");
        return;
    }

    if (strlen($_POST['password']) > 20) {
        echo "Mot de passe doit contenir au plus 20 caractères.";
        header("Location: /register.php");
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([
        ':email' => $email,
    ]);
    $user = $stmt->fetch();
 
    if($user) {
        echo "Cet email ou nom d'utilisateur est déjà utilisé.";
        header("Location: /register.php");
        return;
    }
    

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
        ]);
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: /profile.php");
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { // Code d'erreur pour "Duplicate entry"
            echo "Erreur : Cet email ou nom d'utilisateur est déjà utilisé.";
        } else {
            echo "Erreur lors de l'inscription : " . $e->getMessage();
        }
    }
}
