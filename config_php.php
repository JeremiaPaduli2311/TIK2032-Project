<?php
// Konfigurasi Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blog_pribadi');

// Membuat koneksi ke database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi untuk mencegah SQL injection
function clean($data) {
    global $conn;
    $data = mysqli_real_escape_string($conn, $data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk membuat slug
function createSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

// Fungsi untuk mengecek session
function checkLogin() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Mengaktifkan session
session_start();