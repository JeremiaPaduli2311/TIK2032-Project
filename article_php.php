<?php
// Memuat file konfigurasi
require_once 'config_php.php';

// Mengecek apakah parameter slug tersedia
if (!isset($_GET['slug']) || empty($_GET['slug'])) {
    header("Location: blog.php");
    exit;
}

$slug = clean($_GET['slug']);

// Mengambil data artikel berdasarkan slug
$sql = "SELECT * FROM articles WHERE slug = '$slug'";
$result = mysqli_query($conn, $sql);

// Mengecek apakah artikel ditemukan
if (mysqli_num_rows($result) == 0) {
    header("Location: blog.php");
    exit;
}

// Mengambil data artikel
$article = mysqli_fetch_assoc($result);

// Mengambil artikel terkait dengan kategori yang sama
$category = $article['category'];
$article_id = $article['id'];
$related_sql = "SELECT id, title, slug FROM articles WHERE category = '$category' AND id != $article_id LIMIT 3";
$related_result = mysqli_query($conn, $related_sql);
$related_articles = mysqli_fetch_all($related_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="animation.css" />
    <title><?php echo htmlspecialchars($article['title']); ?> - Blog</title>
    <style>
      /* Gaya untuk artikel */
      .container {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
      }

      .article-header {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
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

      .article-content {
        line-height: 1.6;
      }

      .related-articles {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
      }

      .related-articles h3 {
        margin-bottom: 15px;
      }

      .related-articles ul {
        list-style-type: none;
        padding: 0;
      }

      .related-articles li {
        margin-bottom: 10px;
      }

      .related-articles a {
        color: #3454d1;
        text-decoration: none;
      }

      .related-articles a:hover {
        text-decoration: underline;
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

      .back-button {
        display: inline-block;
        padding: 8px 15px;
        background-color: #3454d1;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-bottom: 20px;
        transition: background-color 0.3s;
      }

      .back-button:hover {
        background-color: #2a3d8f;
      }
      
      /* Animasi scroll */
      .scroll-animation {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s, transform 0.5s;
      }

      .scroll-animation.visible {
        opacity: 1;
        transform: translateY(0);
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
      <a href="blog.php" class="back-button">Kembali ke Blog</a>
      
      <article class="scroll-animation">
        <div class="article-header">
          <h2><?php echo htmlspecialchars($article['title']); ?></h2>
          <div class="article-meta">
            <span class="article-category"><?php echo htmlspecialchars($article['category']); ?></span>
            <span class="article-date"><?php echo date('d M Y', strtotime($article['created_at'])); ?></span>
          </div>
        </div>
        
        <div class="article-content">
          <?php echo $article['content']; ?>
        </div>
      </article>
      
      <?php if(count($related_articles) > 0): ?>
      <div class="related-articles scroll-animation">
        <h3>Artikel Terkait</h3>
        <ul>
          <?php foreach($related_articles as $related): ?>
          <li>
            <a href="article.php?slug=<?php echo $related['slug']; ?>">
              <?php echo htmlspecialchars($related['title']); ?>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
    
    <footer style="background-color: #333; color: #fff; text-align: center; padding: 10px 0;">
      <p>© 2025 Blog Pribadi Saya | Semua hak cipta dilindungi</p>
    </footer>
    
    <script>
      // Script untuk animasi scroll
      document.addEventListener("DOMContentLoaded", function () {
        const scrollElements = document.querySelectorAll(".scroll-animation");

        function checkElements() {
          scrollElements.forEach((element) => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100) {
              element.classList.add("visible");
            }
          });
        }

        // Periksa posisi elemen saat halaman dimuat
        checkElements();

        // Periksa posisi elemen saat halaman di-scroll
        window.addEventListener("scroll", checkElements);

        // Script untuk toggle menu mobile
        const mobileMenu = document.getElementById("mobile-menu");
        const navList = document.querySelector(".nav-list");

        if (mobileMenu) {
          mobileMenu.addEventListener("click", function () {
            navList.classList.toggle("active");
          });
        }
      });
    </script>
  </body>
</html>