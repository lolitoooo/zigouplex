<header>
    <div class="header-container">
        <!-- Logo -->
        <img width="50px" height="50px" src="/asset/zigouplex.webp" alt="Logo de Zigouplex, entreprise innovante en matériaux composites durables" class="logo">

        <!-- Burger Menu Button -->
        <button class="menu-toggle" aria-label="Ouvrir le menu" onclick="toggleMenu()">☰</button>

        <!-- Navigation -->
        <nav class="main-nav" id="main-nav">
            <ul class="menu-list">
            <li><a href="index.php">Accueil</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="achat.php">Acheter</a></li>
                <li><a href="histoire.php">Notre Histoire</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php">Profil</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="auth.php">Connexion</a></li>
                <?php endif; ?>
            </ul>
            </ul>
        </nav>
    </div>
</header>
