<?php
include './core/init.php';
include './core/conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les données actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}

// Gérer la mise à jour des informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':id' => $user_id,
        ]);

        $_SESSION['username'] = $username; // Met à jour le nom d'utilisateur dans la session
        echo "Profil mis à jour avec succès.";
        header("Location: profile.php");
        exit;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) { // Duplicate entry
            echo "Erreur : Cet email ou nom d'utilisateur est déjà utilisé.";
        } else {
            echo "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php $title = "Zigouplex - Modifier le Profil"; include './include/head.php'; ?>
<body>
<?php include './include/header.php'; ?>
    <div class="edit-profile-container">
        <h1>Modifier le Profil</h1>
        <form action="edit_profile.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>
        <a href="profile.php">Retour au profil</a>
    </div>
</body>
</html>
