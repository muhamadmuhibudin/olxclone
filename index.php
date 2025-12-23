<?php
include 'config.php';

try {
    // Ambil lokasi unik dari ads
    $locations = [];
    $loc_stmt = $pdo->query("SELECT DISTINCT location FROM ads WHERE location IS NOT NULL AND location != '' ORDER BY location ASC");
    $locations = $loc_stmt->fetchAll(PDO::FETCH_COLUMN);

    $where = [];
    $params = [];

    if (isset($_GET['title']) && $_GET['title'] != '') {
        $where[] = "ads.title LIKE :title";
        $params[':title'] = '%' . $_GET['title'] . '%';
    }
    if (isset($_GET['location']) && $_GET['location'] != '') {
        $where[] = "ads.location LIKE :location";
        $params[':location'] = '%' . $_GET['location'] . '%';
    }
    if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
        $where[] = "ads.category_id = :category_id";
        $params[':category_id'] = intval($_GET['category_id']);
    }

    $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql = "SELECT ads.*, categories.name AS category_name, 
            (SELECT image_path FROM ad_images WHERE ad_id = ads.id LIMIT 1) AS image_path
            FROM ads 
            JOIN categories ON ads.category_id = categories.id
            $where_sql
            ORDER BY ads.created_at DESC
            LIMIT 12";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => &$val) {
        if ($key === ':category_id') {
            $stmt->bindParam($key, $val, PDO::PARAM_INT);
        } else {
            $stmt->bindParam($key, $val);
        }
    }
    $stmt->execute();
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil kategori untuk menu
    $cat_stmt = $pdo->query("SELECT * FROM categories");
    $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil nama kategori jika ada filter
    $cat_name = '';
    if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
        $cat_id = intval($_GET['category_id']);
        $cat_stmt = $pdo->prepare("SELECT name FROM categories WHERE id = :id");
        $cat_stmt->bindParam(':id', $cat_id, PDO::PARAM_INT);
        $cat_stmt->execute();
        $cat_result = $cat_stmt->fetch(PDO::FETCH_ASSOC);
        $cat_name = $cat_result['name'] ?? '';
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLX Clone - Jual Beli Online Aman dan Nyaman</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #00AA5B;
            --secondary-color: #002F34;
            --light-gray: #F2F4F5;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F2F4F5;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            color: var(--primary-color);
            font-weight: bold;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: white;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            border-radius: 4px;
            font-weight: 500;
            padding: 8px 16px;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-success {
            background-color: var(--primary-color);
            border: none;
            border-radius: 4px;
            font-weight: 500;
            padding: 8px 16px;
        }
        
        .hero-section {
            background-color: var(--secondary-color);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }
        
        .search-box {
            background: white;
            border-radius: 4px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .product-card {
    transition: transform 0.2s ease-in-out;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-img-top {
    object-fit: cover;
    height: 200px;
    width: 100%;
}

.card-title {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    height: 24px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.card-text {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.card-footer {
    padding: 0.75rem 1.25rem;
    background-color: #fff;
    border-top: 1px solid rgba(0,0,0,.125);
}
        
        .product-image {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-price {
            color: var(--secondary-color);
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .location-text {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .category-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .category-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        
        .footer-links h5 {
            color: white;
            margin-bottom: 20px;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #b1b1b1;
            text-decoration: none;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">OLXClone</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="mx-auto">
                    <form class="d-flex" method="GET" action="index.php">
                        <input class="form-control me-2" type="search" name="title" placeholder="Cari di OLXClone" value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '' ?>">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="far fa-comment-dots me-1"></i> Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="far fa-bell me-1"></i> Notifikasi</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="my_ads.php"><i class="fas fa-tag me-1"></i> Iklan Saya</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Akun Saya') ?></a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="logout.php" class="btn btn-success"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="register.php" class="btn btn-success"><i class="fas fa-user-plus me-1"></i> Daftar</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-2">
                        <a href="post-ad.php" class="btn btn-success"><i class="fas fa-plus-circle me-1"></i> Pasang Iklan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 fw-bold mb-4">Jual Beli Online Aman dan Nyaman Hanya di OLXClone</h1>
                    <div class="search-box">
                        <form method="GET" action="index.php" class="row g-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control form-control-lg" name="title" 
                                       value="<?= isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '' ?>" 
                                       placeholder="Apa yang kamu cari?">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg" name="location">
                                    <option value="">Semua Lokasi</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= htmlspecialchars($loc) ?>" <?= (isset($_GET['location']) && $_GET['location'] == $loc) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($loc) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success w-100" type="submit"><i class="fas fa-search me-2"></i>Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kategori Populer -->
    <section class="py-5">
        <div class="container">
            <h4 class="mb-4">Kategori Populer</h4>
            <div class="row">
                <?php foreach ($categories as $cat): ?>
                    <div class="col-6 col-sm-4 col-md-2 mb-4">
                        <a href="index.php?category_id=<?= $cat['id'] ?>" class="text-decoration-none text-dark">
                            <div class="category-card">
                                <div class="category-icon">
                                    <?php
                                    $icon_path = 'assets/images/' . $cat['icon'];
                                    if ($cat['icon'] && file_exists($icon_path)) {
                                        echo '<img src="' . $icon_path . '" alt="' . htmlspecialchars($cat['name']) . '" style="width: 48px; height: 48px; object-fit: contain;">';
                                    } else {
                                        echo '<i class="fas fa-tag"></i>';
                                    }
                                    ?>
                                </div>
                                <h5 class="mt-2"><?= htmlspecialchars($cat['name']) ?></h5>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Iklan Terbaru -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Iklan Terbaru</h4>
                <?php if (isset($_GET['title']) || isset($_GET['location']) || isset($_GET['category_id'])): ?>
                    <a href="index.php" class="btn btn-outline-secondary">Hapus Filter</a>
                <?php else: ?>
                    <a href="#" class="text-decoration-none">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
                <?php endif; ?>
            </div>

            <?php
            $info = [];
            if (isset($_GET['title']) && $_GET['title'] != '') $info[] = "Judul: <b>" . htmlspecialchars($_GET['title']) . "</b>";
            if (isset($_GET['location']) && $_GET['location'] != '') $info[] = "Lokasi: <b>" . htmlspecialchars($_GET['location']) . "</b>";
            if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
                $info[] = "Kategori: <b>" . htmlspecialchars($cat_name) . "</b>";
            }
            if ($info) echo "<p>Filter: " . implode(', ', $info) . "</p>";
            ?>

            <?php
            if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
                echo "<p>Menampilkan iklan untuk kategori: <b>" . htmlspecialchars($cat_name) . "</b></p>";
            }
            ?>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($ads as $ad): ?>
        <div class="col">
            <div class="card h-100 product-card">
                <div class="position-relative" style="height: 200px; overflow: hidden;">
                    <?php
                    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
                    $imagePath = '';
                    
                    if (!empty($ad['image_path'])) {
                        $cleanPath = ltrim($ad['image_path'], '/');
                        $imagePath = strpos($cleanPath, 'uploads/ads/') === 0 ? $cleanPath : 'uploads/ads/' . $cleanPath;
                        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/OLXCLONE/' . ltrim($imagePath, '/');
                        
                        if (!file_exists($fullPath) || !is_file($fullPath)) {
                            $imagePath = 'https://placehold.co/400x300/e9ecef/6c757d?text=Gambar+Tidak+Ditemukan';
                        } else {
                            $imagePath = $baseUrl . '/OLXCLONE/' . ltrim($imagePath, '/');
                        }
                    } else {
                        $imagePath = 'https://placehold.co/400x300/e9ecef/6c757d?text=No+Image';
                    }
                    ?>
                    <img src="<?= htmlspecialchars($imagePath) ?>" 
                         class="card-img-top h-100" 
                         alt="<?= htmlspecialchars($ad['title']) ?>"
                         style="object-fit: cover;"
                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/e9ecef/6c757d?text=Gagal+Memuat+Gambar';">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate"><?= htmlspecialchars($ad['title']) ?></h5>
                    <p class="card-text fw-bold text-primary">Rp <?= number_format($ad['price'], 0, ',', '.') ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($ad['location']) ?>
                        </small>
                        <small class="text-muted">
                            <?php 
                            $created = new DateTime($ad['created_at']);
                            $now = new DateTime();
                            $interval = $now->diff($created);
                            
                            if ($interval->days == 0) {
                                echo 'Hari ini ' . $created->format('H:i');
                            } elseif ($interval->days == 1) {
                                echo 'Kemarin ' . $created->format('H:i');
                            } else {
                                echo $interval->days . ' hari lalu';
                            }
                            ?>
                        </small>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a href="detail.php?id=<?= $ad['id'] ?>" class="btn btn-outline-primary w-100">Lihat Detail</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
        </div>
    </section>

    <!-- Mobile App Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-3">Download Aplikasi OLXClone Sekarang</h3>
                    <p class="mb-4">Beli dan jual mudah di mana saja dan kapan saja dengan aplikasi OLXClone. Unduh sekarang!</p>
                    <div class="d-flex">
                        <a href="#" class="me-3">
                            <img src="https://img.icons8.com/?size=100&id=fKXXelWgP1B6&format=png&color=000000" alt="App Store" style="height: 40px;">
                        </a>
                        <a href="#">
                            <img src="https://img.icons8.com/?size=100&id=rZwnRdJyYqRi&format=png&color=000000" alt="Google Play" style="height: 40px;">
                        </a>
                    </div>
                </div>
                <div class="col-md-6 text-end d-none d-md-block">
                    <img src="https://img.icons8.com/?size=100&id=WqDlTg5quiXg&format=png&color=000000" alt="OLXClone App" class="img-fluid" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5>OLXClone</h5>
                    <p class="text-muted">OLXClone adalah platform jual beli online terpercaya di Indonesia.</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="footer-links">
                        <h5>Tentang Kami</h5>
                        <ul>
                            <li><a href="#">Tentang OLXClone</a></li>
                            <li><a href="#">Karier</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="footer-links">
                        <h5>Bantuan</h5>
                        <ul>
                            <li><a href="#">Pusat Bantuan</a></li>
                            <li><a href="#">Syarat dan Ketentuan</a></li>
                            <li><a href="#">Panduan Keamanan</a></li>
                            <li><a href="#">Hubungi Kami</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="footer-links">
                        <h5>Ikuti Kami</h5>
                        <div class="social-links">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; 2025 OLXClone. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>