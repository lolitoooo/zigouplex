<?php
include './core/init.php';
include './core/conn.php';

if($_SESSION['admin'] !== "true") {
    header("Location: /achat.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $stock = htmlspecialchars($_POST['stock']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowedFileExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array(strtolower($fileNameCmps), $allowedFileExtensions)) {
            $uploadPath = './uploads/' . time() . '_' . $fileName;
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $image_url = $uploadPath;
            } else {
                $error = "Erreur lors de l'upload de l'image.";
            }
        } else {
            $error = "Extension de fichier non autorisée. Utilisez jpg, jpeg, png, ou webp.";
        }
    } else {
        $image_url = null;
    }

    if (!empty($title) && !empty($content) && isset($image_url)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO product (title, description, price, stock, image_url, user_id) VALUES (:title, :description, :price, :stock, :image_url, :user_id)");
            $stmt->execute([
                ':title' => $title,
                ':description' => $content,
                ':price' => $price,
                ':stock' => $stock,
                ':image_url' => $image_url,
                ':user_id' => $_SESSION['user_id']
            ]);
            header("Location: achat.php");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout du produit : " . $e->getMessage();
        }
    } else {
        var_dump($content);
        $error = "Tous les champs sont requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php $title = "Zigouplex - Achat"; include './include/head.php'; ?>
<body>
<?php include './include/header.php'; ?>
    <h1>Ajouter un produit</h1>
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error; ?></p>
    <?php endif; ?>
    <main class="add-product-container">
        <h1>Ajouter un Produit</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <label for="title">Nom du produit :</label>
            <input type="text" id="title" name="title" required>
            
            <label for="description">Description :</label>
            <textarea id="description" name="description" rows="5" required></textarea>
            
            <label for="price">Prix :</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>
            
            <label for="stock">Quantité en stock :</label>
            <input type="number" id="stock" name="stock" min="0" required>
            
            <label for="image">Image du produit :</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <button type="submit">Ajouter le produit</button>
        </form>
    </main>
</body>
</html>
