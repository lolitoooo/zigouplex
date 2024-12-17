<?php 
include './core/init.php';
include './core/conn.php';

$articles_per_page = 4; // Nombre d'articles par page
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max($current_page, 1); // Empêche d'utiliser une page inférieure à 1

// Calcul de l'offset
$offset = ($current_page - 1) * $articles_per_page;

// Récupérer les articles pour la page actuelle
$stmt = $pdo->prepare("
    SELECT id, title, description, price, image_url, stock, created_at, is_active 
    FROM product 
    WHERE is_active = 1 
    ORDER BY created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $articles_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

$total_products_stmt = $pdo->query("SELECT COUNT(*) FROM product");
$total_products = $total_products_stmt->fetchColumn();
$total_pages = ceil($total_products / $articles_per_page);

function truncateDescription($description, $maxLength = 100) {
    if (strlen($description) <= $maxLength) {
        return $description;
    }
    $truncated = substr($description, 0, $maxLength);
    return substr($truncated, 0, strrpos($truncated, ' ')) . '...';
}

?>
<!DOCTYPE html>
<html lang="fr">
<?php $title = "Zigouplex - Achat"; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <main>
        <h1>Nos Produits Zigouplex</h1>
        <?php if ($_SESSION['admin'] == "true"): ?>
            <a href="add_product.php" class="btn">Ajouter un Produit</a>
        <?php endif; ?>
        <div class="grid">
            <?php foreach ($products as $product): ?>
                <?php
                    $excerpt = truncateDescription($product['description'], 100);
                ?>
                <div class="card">
                    <img src="<?= htmlspecialchars($product['image_url'] ?: ''); ?>" 
                        alt="<?= htmlspecialchars($product['title']); ?>">
                    <h3><?= htmlspecialchars($product['title']); ?></h3>
                    <p><?= $excerpt; ?></p>
                    <p><strong>Prix : </strong><?= htmlspecialchars($product['price']); ?> €</p>
                    <a href="#">Acheter maintenant</a>
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
    <?php include './include/footer.php'; ?>
</body>
</html>