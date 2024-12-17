<?php
include './core/init.php';
include './core/conn.php';

if($_SESSION['admin'] !== "true") {
    header("Location: /blog.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, user_id) VALUES (:title, :content, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':user_id' => $_SESSION['user_id'],
        ]);
        header("Location: blog.php");
        exit;
    } else {
        $error = "Tous les champs sont requis.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php $title = "Zigouplex - Ajouter un Article"; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <main class="add-article-container">
        <h1>Ajouter un Article</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="add_article.php" method="POST">
            <label for="title">Titre de l'article :</label>
            <input type="text" id="title" name="title" required>
            
            <label for="content">Contenu :</label>
            <textarea id="content" name="content" rows="10" required></textarea>
            
            <button type="submit">Publier</button>
        </form>
    </main>
</body>
</html>
