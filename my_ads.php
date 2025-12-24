<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("
    SELECT 
        ads.*, 
        (SELECT image_path FROM ad_images WHERE ad_id=ads.id LIMIT 1) AS image_path, 
        categories.name AS category_name 
    FROM ads 
    JOIN categories ON ads.category_id = categories.id 
    WHERE ads.user_id = :user_id 
    ORDER BY ads.created_at DESC
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iklan Saya - OLXClone</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #002f34;
            --secondary-color: #23e5db;
            --light-gray: #f2f4f5;
        }
        
        body {
            background-color: #f7f8f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        .ad-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .ad-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .ad-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .ad-body {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .ad-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 52px;
        }
        
        .ad-price {
            color: #000;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .ad-location, .ad-category {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .ad-actions {
            margin-top: auto;
            padding: 10px 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 8px;
        }
        
        .btn-edit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        
        .btn-edit:hover {
            background-color: #001f23;
            color: white;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        
        .btn-delete:hover {
            background-color: #bb2d3b;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 15px;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<?php include 'includes/navbar.php'; ?>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-list me-2"></i>Iklan Saya</h2>
            <a href="post-ad.php" class="btn" style="background-color: var(--primary-color); color: white;">
                <i class="fas fa-plus me-1"></i> Pasang Iklan Baru
            </a>
        </div>
        
        <?php if (empty($ads)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Belum ada iklan</h4>
                <p class="text-muted">Anda belum memiliki iklan yang dipasang. Klik tombol di atas untuk memulai.</p>
                <a href="post-ad.php" class="btn mt-3" style="background-color: var(--primary-color); color: white;">
                    <i class="fas fa-plus me-1"></i> Pasang Iklan
                </a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                <?php foreach($ads as $ad): ?>
                    <div class="col">
                        <div class="ad-card h-100">
                            <a href="detail.php?id=<?= $ad['id'] ?>" style="text-decoration: none; color: inherit;">
                                <img src="<?= !empty($ad['image_path']) ? 'uploads/' . $ad['image_path'] : 'https://placehold.co/600x400?text=No+Image' ?>" 
     class="ad-image" 
     alt="<?= htmlspecialchars($ad['title']) ?>">
                                <div class="ad-body">
                                    <h5 class="ad-title"><?= htmlspecialchars($ad['title']) ?></h5>
                                    <div class="ad-price">Rp <?= number_format($ad['price'], 0, ',', '.') ?></div>
                                    <div class="ad-category">
                                        <i class="fas fa-tag me-1"></i> <?= htmlspecialchars($ad['category_name']) ?>
                                    </div>
                                    <div class="ad-location">
                                        <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($ad['location']) ?>
                                    </div>
                                </div>
                            </a>
                            <div class="ad-actions">
                                <a href="edit_ad.php?id=<?= $ad['id'] ?>" class="btn-edit w-50 text-center">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <a href="delete_ad.php?id=<?= $ad['id'] ?>" 
                                   class="btn-delete w-50 text-center"
                                   onclick="return confirm('Yakin ingin menghapus iklan ini?')">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>OLXClone</h5>
                    <p class="text-muted">Tempat jual beli online aman dan nyaman dengan beragam pilihan produk terbaik.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>Tentang Kami</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Tentang OLXClone</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Syarat dan Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Bantuan</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Cara Berjualan</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Panduan Keamanan</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Download Aplikasi</h6>
                    <div class="d-flex gap-2 mb-3">
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-google-play me-1"></i> Google Play</a>
                        <a href="#" class="btn btn-outline-light btn-sm"><i class="fab fa-apple me-1"></i> App Store</a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>© 2023 OLXClone. Hak Cipta Dilindungi.</small>
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