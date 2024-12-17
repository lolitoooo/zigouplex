<?php
include '../core/conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if($password == "" || $email == "") {
        echo "Veuillez remplir tous les champs.";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if($user['role'] == 2) {
                $_SESSION['admin'] = "true";
            } else {
                $_SESSION['admin'] = "false";
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Connexion réussie ! Bienvenue, " . htmlspecialchars($user['username']) . ".";
            header("Location: /profile.php");
        } else {
            echo "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la connexion : " . $e->getMessage();
    }
}
