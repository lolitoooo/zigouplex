<?php
include './core/init.php';
include './core/conn.php';

// Vérifier si un ID est passé dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: blog.php");
    exit;
}

// Récupérer l'article depuis la base de données
$stmt = $pdo->prepare("SELECT title, content, created_at FROM articles WHERE id = :id");
$stmt->execute([':id' => $_GET['id']]);
$article = $stmt->fetch();

if (!$article) {
    echo "Article introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php $title = htmlspecialchars($article['title']) . " - Zigouplex"; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <main class="view-article-container">
        <h1><?= htmlspecialchars($article['title']); ?></h1>
        <p class="article-date">Publié le <?= htmlspecialchars($article['created_at']); ?></p>
        <div class="article-content">
            <?= nl2br(htmlspecialchars($article['content'])); ?>
        </div>
        <a href="blog.php" class="btn">Retour au blog</a>
    </main>
</body>
</html>
