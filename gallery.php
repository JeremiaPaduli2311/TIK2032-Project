<?php
// Memuat file konfigurasi
require_once 'config_php.php';

// Mengambil data galeri dari database
$sql = "SELECT id, title, filename, description FROM gallery ORDER BY upload_date DESC";
$result = mysqli_query($conn, $sql);
$gallery_images = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="animation.css" />
    <title>Galeri</title>
    <style>
      /* Gaya untuk galeri */
      .container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
      }

      .gallery-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 20px 0;
      }

      .gallery-item {
        width: 100%;
        max-width: 300px;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
        opacity: 0; /* Awalnya tersembunyi */
        transform: translateY(20px); /* Awalnya bergeser ke bawah */
        transition: opacity 0.5s, transform 0.5s;
      }

      .gallery-item.visible {
        opacity: 1; /* Menampilkan gambar */
        transform: translateY(0); /* Kembali ke posisi normal */
      }

      .gallery-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.3s;
      }

      .gallery-item img:hover {
        transform: scale(1.05);
      }

      .gallery-item h4 {
        margin: 10px 0 5px 0;
        font-size: 18px;
      }

      .gallery-item p {
        margin: 0;
        color: #666;
        font-size: 14px;
      }

      /* Gaya untuk modal */
      .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8);
      }

      .modal-content {
        margin: 5% auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
      }

      .modal-caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: white;
        padding: 10px 0;
        height: 150px;
      }

      .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
      }

      .close:hover,
      .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
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
    </style>
  </head>
  <body>
    <header>
      <h1>Galeri</h1>
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
      <h2>Kumpulan Foto</h2>
      
      <div class="gallery-container">
        <?php if(count($gallery_images) > 0): ?>
          <?php foreach($gallery_images as $index => $image): ?>
            <div class="gallery-item" data-delay="<?php echo $index * 100; ?>">
              <img 
                src="<?php echo htmlspecialchars($image['filename']); ?>" 
                alt="<?php echo htmlspecialchars($image['title']); ?>" 
                class="gallery-image"
                data-id="<?php echo $image['id']; ?>"
                data-title="<?php echo htmlspecialchars($image['title']); ?>"
                data-description="<?php echo htmlspecialchars($image['description']); ?>"
              />
              <h4><?php echo htmlspecialchars($image['title']); ?></h4>
              <p><?php echo htmlspecialchars($image['description']); ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Belum ada gambar yang tersedia di galeri.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Modal untuk menampilkan gambar besar -->
    <div id="imageModal" class="modal">
      <span class="close">&times;</span>
      <img class="modal-content" id="modalImage">
      <div id="modalCaption" class="modal-caption"></div>
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
    
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        // JavaScript untuk menu responsif
        const mobileMenu = document.getElementById("mobile-menu");
        const navList = document.querySelector(".nav-list");

        if (mobileMenu) {
          mobileMenu.addEventListener("click", function() {
            navList.classList.toggle("active");
          });
        }

        // JavaScript untuk animasi galeri
        const galleryItems = document.querySelectorAll(".gallery-item");
        
        function showGalleryItems() {
          galleryItems.forEach((item) => {
            const delay = parseInt(item.getAttribute('data-delay')) || 0;
            
            setTimeout(() => {
              item.classList.add("visible");
            }, delay);
          });
        }
        
        // Menampilkan item galeri dengan efek delay
        showGalleryItems();

        // JavaScript untuk modal
        const modal = document.getElementById("imageModal");
        const modalImg = document.getElementById("modalImage");
        const modalCaption = document.getElementById("modalCaption");
        const images = document.querySelectorAll(".gallery-image");

        images.forEach((image) => {
          image.addEventListener("click", function() {
            modal.style.display = "block";
            modalImg.src = this.src;
            modalCaption.innerHTML = "<h3>" + this.getAttribute('data-title') + "</h3><p>" + 
                                    this.getAttribute('data-description') + "</p>";
          });
        });

        const closeButton = document.getElementsByClassName("close")[0];
        closeButton.onclick = function() {
          modal.style.display = "none";
        };

        // Tutup modal jika user klik di luar gambar
        window.onclick = function(event) {
          if (event.target == modal) {
            modal.style.display = "none";
          }
        };
      });
    </script>
  </body>
</html>