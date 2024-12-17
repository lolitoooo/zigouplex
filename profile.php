<?php
include './core/init.php';
include './core/conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php $title = 'Zigouplex - Profil'; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <div class="profile-container">
        <h1>Profil de <?= htmlspecialchars($user['username']); ?></h1>
        <div class="profile-details">
            <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']); ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Membre depuis :</strong> <?= htmlspecialchars($user['created_at']); ?></p>
        </div>
        <div class="profile-actions">
            <a href="edit_profile.php">Modifier le profil</a>
            <a href="logout.php">Se déconnecter</a>
            <?php if ($_SESSION['admin'] == "true"): ?>
                <a href="dashboard.php">Admin</a>
            <?php endif; ?>
        </div>
    </div>
    <?php include './include/footer.php'; ?>
</body>
</html>
