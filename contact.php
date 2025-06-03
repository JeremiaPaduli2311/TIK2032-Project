<?php
require_once 'config_php.php';

function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

$message_sent = false;
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $message = clean($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "Silakan lengkapi semua field.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
            if (mysqli_stmt_execute($stmt)) {
                $message_sent = true;
            } else {
                $error_message = "Terjadi kesalahan saat menyimpan pesan.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Terjadi kesalahan pada query database.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="animation.css" />
    <title>Hubungi Saya</title>
    <style>
      /* Gaya untuk form kontak */
      .container {
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
      }

      form {
        display: flex;
        flex-direction: column;
        gap: 10px;
      }

      input,
      textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
        max-width: 600px;
      }

      button {
        padding: 10px;
        background-color: #3454d1;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 200px;
      }

      button:hover {
        background-color: #2a3d8f;
      }

      .success-message {
        padding: 10px;
        background-color: #d4edda;
        color: #155724;
        border-radius: 5px;
        margin-bottom: 15px;
      }

      .error-message {
        padding: 10px;
        background-color: #f8d7da;
        color: #721c24;
        border-radius: 5px;
        margin-bottom: 15px;
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

      /* Gaya untuk efek scroll animasi */
      .scroll-animation {
        opacity: 0; /* Awalnya tersembunyi */
        transform: translateY(20px); /* Awalnya bergeser ke bawah */
        transition: opacity 0.5s, transform 0.5s;
      }

      .scroll-animation.visible {
        opacity: 1; /* Menampilkan elemen */
        transform: translateY(0); /* Kembali ke posisi normal */
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Hubungi Saya</h1>
      <nav>
        <div class="menu-toggle" id="mobile-menu">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="nav-list">
          <li><a href="index.html">Beranda</a></li>
          <li><a href="gallery.html">Galeri</a></li>
          <li><a href="blog.html">Blog</a></li>
          <li><a href="contact.php">Hubungi Saya</a></li>
          <?php if(isset($_SESSION['user_id'])): ?>
          <li><a href="admin/dashboard.php">Admin</a></li>
          <li><a href="logout.php">Logout</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </header>
    <div class="container scroll-animation">
      <h2>Hubungi Saya</h2>
      
      <?php if($message_sent): ?>
        <div class="success-message">
          <p>Pesan Anda telah berhasil dikirim! Terima kasih telah menghubungi saya.</p>
        </div>
      <?php endif; ?>
      
      <?php if(!empty($error_message)): ?>
        <div class="error-message">
          <p><?php echo $error_message; ?></p>
        </div>
      <?php endif; ?>
      
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
          <label for="name">Nama:</label>
          <input type="text" id="name" name="name" placeholder="Nama Anda" required />
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Email Anda" required />
        </div>
        <div>
          <label for="message">Pesan:</label>
          <textarea id="message" name="message" placeholder="Tulis pesan Anda di sini..." rows="5" required></textarea>
        </div>
        <button type="submit">Kirim Pesan</button>
      </form>
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
      // JavaScript untuk menu responsif
      document.addEventListener("DOMContentLoaded", function() {
        const mobileMenu = document.getElementById("mobile-menu");
        const navList = document.querySelector(".nav-list");

        if (mobileMenu) {
          mobileMenu.addEventListener("click", function() {
            navList.classList.toggle("active");
          });
        }

        // JavaScript untuk efek scroll animasi
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
      });
    </script>
  </body>
</html>