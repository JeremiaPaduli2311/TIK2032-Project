<?php
// Memuat file konfigurasi
require_once 'config_php.php';

// Mengambil 3 artikel terbaru dari database
$sql = "SELECT id, title, slug, intro_text, category FROM articles ORDER BY created_at DESC LIMIT 3";
$result = mysqli_query($conn, $sql);
$latest_articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="animation.css" />
    <title>Jere's Blog</title>
    <style>
      /* Tambahkan CSS untuk menu toggle */
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

      /* Scroll Animation */
      .scroll-animation {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s, transform 0.5s;
      }

      .scroll-animation.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* Style untuk artikel terbaru */
      .latest-article {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
      }

      .latest-article h4 {
        margin-bottom: 10px;
        color: #333;
      }

      .latest-article p {
        margin-bottom: 10px;
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

      .profile-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 20px;
        margin-bottom: 15px;
        float: left;
        border: 3px solid #3454d1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .about {
        overflow: hidden;
        margin-bottom: 30px;
      }

      @media (max-width: 600px) {
        .profile-image {
          float: none;
          display: block;
          margin: 0 auto 20px auto;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Selamat Datang di Blog Pribadi Saya</h1>
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
      <h2>Catatan Kehidupan & Pemikiran</h2>
      <p>
        Terima kasih telah mengunjungi ruang digital pribadi saya. Di sini saya
        berbagi pemikiran, pengalaman, dan hal-hal yang saya minati.
      </p>

      <section class="about scroll-animation">
        <h3>Tentang Saya</h3>
        <img src="IMG_9404.JPEG" alt="Foto Saya" class="profile-image" />
        <p>
          Halo! Saya seorang penulis, penggemar teknologi, dan pencinta seni
          yang senang berbagi pengalaman dan pengetahuan melalui blog ini. Saya
          percaya bahwa berbagi ide adalah cara terbaik untuk belajar dan tumbuh
          bersama.
        </p>
        <p>
          Blog ini adalah wadah ekspresi pribadi saya tentang berbagai topik
          yang saya minati, mulai dari teknologi pertanian, seni, hingga
          kesehatan. Saya harap tulisan-tulisan saya bisa menginspirasi dan
          bermanfaat bagi Anda.
        </p>
      </section>

      <section class="interests scroll-animation">
        <h3>Topik Bahasan</h3>
        <ul>
          <li>Teknologi & Inovasi</li>
          <li>Seni & Kreativitas</li>
          <li>Kesehatan & Gaya Hidup</li>
          <li>Pengembangan Diri</li>
        </ul>
      </section>

      <section class="latest-posts scroll-animation">
        <h3>Tulisan Terbaru</h3>

        <?php foreach($latest_articles as $article): ?>
        <div class="latest-article">
          <h4><?php echo htmlspecialchars($article['title']); ?></h4>
          <p><?php echo htmlspecialchars($article['intro_text']); ?></p>
          <a href="article.php?slug=<?php echo $article['slug']; ?>" class="button">Baca Selengkapnya</a>
        </div>
        <?php endforeach; ?>
      </section>
    </div>

    <footer
      style="
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px 0;
      "
    >
      <p>© 2025 Blog Pribadi Saya | Semua hak cipta dilindungi</p>
    </footer>
    <script src="main.js"></script>

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