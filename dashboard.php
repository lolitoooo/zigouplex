<?php
session_start();
include './core/init.php'; 
include './core/conn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

if ($_SESSION['admin'] != "true") {
    header("Location: index.php");
    exit;
}

// Récupération des statistiques globales
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM product");
$total_products = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM articles");
$total_articles = $stmt->fetchColumn();

// Récupération des données pour les graphiques
$user_stats = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM users GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$product_stats = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM product GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
$article_stats = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM articles GROUP BY DATE(created_at) ORDER BY date DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<?php $title = "Zigouplex - Dashboard "; include './include/head.php'; ?>
<body>
    <?php include './include/header.php'; ?>
    <main>
        <h1>Tableau de bord</h1>
        <section class="dashboard">
            <h2>Bienvenue, <?= $_SESSION['username']; ?></h2>
            <p>Vous êtes connecté en tant qu'administrateur.</p>

            <!-- Statistiques générales -->
            <div class="stats">
                <div class="stat">
                    <h3><?= $total_users; ?></h3>
                    <p>Utilisateurs</p>
                </div>
                <div class="stat">
                    <h3><?= $total_products; ?></h3>
                    <p>Produits</p>
                </div>
                <div class="stat">
                    <h3><?= $total_articles; ?></h3>
                    <p>Articles</p>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="charts">
                <h3>Statistiques d'inscription des utilisateurs</h3>
                <canvas id="userChart"></canvas>

                <h3>Statistiques des produits ajoutés</h3>
                <canvas id="productChart"></canvas>

                <h3>Statistiques des articles publiés</h3>
                <canvas id="articleChart"></canvas>
            </div>

            <!-- Liens vers la gestion -->
            <div class="management-links">
                <a href="users.php" class="btn">Gérer les utilisateurs</a>
                <a href="products.php" class="btn">Gérer les produits</a>
                <a href="articles.php" class="btn">Gérer les articles</a>
            </div>
        </section>
    </main>
<?php include './include/footer.php'; ?>
<script>
// Préparer les données pour Chart.js
const userChartData = <?= json_encode($user_stats); ?>;
const productChartData = <?= json_encode($product_stats); ?>;
const articleChartData = <?= json_encode($article_stats); ?>;

// Fonction pour formater les données
function formatChartData(data) {
    return {
        labels: data.map(item => item.date),
        datasets: [{
            label: 'Nombre',
            data: data.map(item => item.count),
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };
}

// Configuration des graphiques
const userCtx = document.getElementById('userChart').getContext('2d');
new Chart(userCtx, {
    type: 'bar',
    data: formatChartData(userChartData),
    options: { responsive: true }
});

const productCtx = document.getElementById('productChart').getContext('2d');
new Chart(productCtx, {
    type: 'line',
    data: formatChartData(productChartData),
    options: { responsive: true }
});

const articleCtx = document.getElementById('articleChart').getContext('2d');
new Chart(articleCtx, {
    type: 'line',
    data: formatChartData(articleChartData),
    options: { responsive: true }
});
</script>
</body>
</html>
