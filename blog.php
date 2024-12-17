<?php
include './core/init.php';
include './core/conn.php';


$articles_per_page = 5;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max($current_page, 1);

$offset = ($current_page - 1) * $articles_per_page;

$stmt = $pdo->prepare("SELECT id, title, content FROM articles ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $articles_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();

$total_articles_stmt = $pdo->query("SELECT COUNT(*) FROM articles");
$total_articles = $total_articles_stmt->fetchColumn();
$total_pages = ceil($total_articles / $articles_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<?php $title = "Zigouplex - Blog"; include './include/head.php'; ?>

<body>
    <?php include './include/header.php'; ?>
    <main class="blog-container">
        <h1>Blog de Zigouplex</h1>
        <?php if ($_SESSION['admin'] == "true"): ?>
            <a href="add_article.php" class="btn">Ajouter un Article</a>
        <?php endif; ?>

        <div class="articles-list">
            <?php foreach ($articles as $article): ?>
                <div class="article-preview">
                    <h2><a href="view_article.php?id=<?= $article['id']; ?>">
                        <?= htmlspecialchars($article['title']); ?>
                    </a></h2>
                    <p><?= htmlspecialchars(substr($article['content'], 0, 200)) . '...'; ?></p>
                    <a href="view_article.php?id=<?= $article['id']; ?>" class="read-more">Lire la suite</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1; ?>" class="btn">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i; ?>" class="btn <?= $i === $current_page ? 'active' : ''; ?>">
                    <?= $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1; ?>" class="btn">Suivant</a>
            <?php endif; ?>
        </div>
    </main>
    <?php include './include/footer.php' ?>
</body>
</html>
