<?php
// Memuat file konfigurasi
require_once 'config_php.php';

// Mengambil semua artikel dari database
$sql = "SELECT id, title, slug, intro_text, category, created_at FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="animation.css" />
    <title>Blog</title>
    <style>
      /* Gaya untuk artikel */
      .container {
        padding: 20px;
      }

      article {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        transition: opacity 0.5s, transform 0.5s;
        opacity: 0; /* Awalnya tersembunyi */
        transform: translateY(20px); /* Awalnya bergeser ke bawah */
      }

      article.visible {
        opacity: 1; /* Menampilkan artikel */
        transform: translateY(0); /* Kembali ke posisi normal */
      }

      .article-meta {
        font-size: 0.9em;
        color: #777;
        margin-bottom: 10px;
      }

      .article-category {
        display: inline-block;
        padding: 3px 8px;
        background-color: #f0f0f0;
        border-radius: 3px;
        margin-right: 10px;
      }

      /* Gaya untuk menu toggle */
      .menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        align-items: center; /* Center the toggle */
        margin: 0 auto; /* Center the toggle horizontally */
      }

      .menu-toggle .bar {
        height: 3px;
        width: 25px;
        background-color: #333;
        margin: 3px 0;
      }

      nav {
        display: flex;
        justify-content: center; /* Center the nav items */
        align-items: center;
        flex-direction: column; /* Stack items vertically */
      }

      nav a {
        margin: 0 15px;
        text-decoration: none;
        color: #333;
      }

      @media (max-width: 800px) {
        nav ul {
          display: none;
          flex-direction: column;
          width: 100%;
        }

        nav ul.active {
          display: flex;
        }

        .menu-toggle {
          display: flex;
        }
      }

      .button {
        display: inline-block;
        padding: 8px 15px;
        background-color: #3454d1;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
      }

      .button:hover {
        background-color: #2a3d8f;
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Blog</h1>
      <nav>
        <div class="menu-toggle" id="mobile-menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="nav-list">
          <li><a href="index.php">Beranda</a></li>
          <li><a href="gallery.php">Galeri</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="contact.php">Hubungi Saya</a></li>
          <?php if(isset($_SESSION['user_id'])): ?>
          <li><a href="admin/dashboard.php">Admin</a></li>
          <li><a href="logout.php">Logout</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>
    <div class="container">
      <h2>Artikel Terbaru</h2>
      
      <?php if(count($articles) > 0): ?>
        <?php foreach($articles as $article): ?>
          <article class="scroll-animation">
            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
            <div class="article-meta">
              <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
              <span class="article-date"><?php echo date('d M Y', strtotime($article['created_at'])); ?></span>
            </div>
            <p><?php echo htmlspecialchars($article['intro_text']); ?></p>
            <a href="article.php?slug=<?php echo $article['slug']; ?>" class="button">Baca Selengkapnya</a>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Belum ada artikel yang tersedia.</p>
      <?php endif; ?>
    </div>
    <footer
      style="
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px 0;
      "
    >
      <p>© 2025 Website Saya</p>
    </footer>
    <script src="main.js"></script>
  </body>
</html>