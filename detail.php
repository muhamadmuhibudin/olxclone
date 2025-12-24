<?php
session_start();
include 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<script>alert('Iklan tidak ditemukan!');window.location='index.php';</script>";
    exit;
}

try {
    // Ambil data iklan menggunakan PDO
    $sql = "SELECT ads.*, categories.name AS category_name, users.name AS user_name, users.whatsapp
            FROM ads
            JOIN categories ON ads.category_id = categories.id
            JOIN users ON ads.user_id = users.id
            WHERE ads.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ad) {
        echo "<script>alert('Iklan tidak ditemukan!');window.location='index.php';</script>";
        exit;
    }

    // Ambil gambar iklan menggunakan PDO
    $img_stmt = $pdo->prepare("SELECT image_path FROM ad_images WHERE ad_id = :ad_id LIMIT 1");
    $img_stmt->bindParam(':ad_id', $id, PDO::PARAM_INT);
    $img_stmt->execute();
    $img_row = $img_stmt->fetch(PDO::FETCH_ASSOC);
    
    // Debug: Tampilkan path gambar untuk pengecekan
    if ($img_row && !empty($img_row['image_path'])) {
        // Gunakan path yang sudah ada di database (sudah termasuk 'uploads/ads/')
        $image_path = $img_row['image_path'];
        
        // Debug: Tampilkan path yang akan digunakan
        error_log("Trying to load image from: " . $image_path);
        
        // Pastikan file ada
        if (!file_exists($image_path)) {
            error_log("Image not found: " . $image_path);
            $image_path = 'https://placehold.co/600x400';
        } else {
            error_log("Image found at: " . $image_path);
        }
    } else {
        $image_path = 'https://placehold.co/600x400';
        error_log("No image record found in database for ad ID: " . $id);
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ad['title']) ?> - OLXClone</title>
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
        
        .ad-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .price-tag {
            font-size: 28px;
            font-weight: 700;
            color: #000;
            margin: 15px 0;
        }
        
        .seller-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .ad-description {
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .ad-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
            object-fit: cover;
        }
        
        .btn-whatsapp {
            background-color: #25D366;
            color: white;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-whatsapp:hover {
            background-color: #128C7E;
            color: white;
        }
        
        .detail-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-weight: 500;
            margin-bottom: 15px;
        }
        
        .nav-link {
            color: #002f34;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: #23e5db;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<?php include 'includes/navbar.php'; ?>
    <!-- Main Content -->
    <div class="container my-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none"><?= htmlspecialchars($ad['category_name']) ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($ad['title']) ?></li>
            </ol>
        </nav>
        
        <div class="row">
            <!-- Left Column - Images -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($ad['title']) ?>" class="ad-image">
                    </div>
                </div>
                
                <!-- Description Section -->
                <div class="ad-description">
                    <h4 class="mb-4">Deskripsi</h4>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($ad['description'])) ?></p>
                </div>
            </div>
            
            <!-- Right Column - Details -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h1 class="ad-title"><?= htmlspecialchars($ad['title']) ?></h1>
                        <div class="price-tag">Rp <?= number_format($ad['price'], 0, ',', '.') ?></div>
                        
                        <div class="seller-info">
                            <h6 class="fw-bold mb-3">Info Penjual</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <i class="fas fa-user-circle fa-3x text-secondary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($ad['user_name']) ?></h6>
                                    <small class="text-muted">Member sejak 2023</small>
                                </div>
                            </div>
                            <?php
// ===== NORMALISASI NOMOR WA (TAMBAH SEKALI SAJA) =====
$wa_penjual = $ad['whatsapp'] ?? '';

// hanya proses jika tidak kosong
if ($wa_penjual !== '' && str_starts_with($wa_penjual, '0')) {
    $wa_penjual = '62' . substr($wa_penjual, 1);
}


$pesan = urlencode(
    "Saya tertarik dengan iklan {$ad['title']} di OLXClone"
);
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <!-- ===== USER SUDAH LOGIN ===== -->
    <a 
        href="https://wa.me/<?= htmlspecialchars($wa_penjual) ?>?text=<?= $pesan ?>"
        class="btn btn-whatsapp"
        target="_blank"
    >
        <i class="fab fa-whatsapp me-2"></i> Chat via WhatsApp
    </a>

<?php else: ?>
    <!-- ===== USER BELUM LOGIN ===== -->
    <a 
        href="login.php?redirect=detail.php?id=<?= $ad['id'] ?>"
        class="btn btn-outline-secondary w-100"
    >
        <i class="fas fa-lock me-2"></i> Login untuk chat penjual
    </a>
<?php endif; ?>

                        </div>
                        
                        <div class="mt-4">
                            <div class="detail-label">Lokasi</div>
                            <div class="detail-value">
                                <i class="fas fa-map-marker-alt me-2"></i> <?= htmlspecialchars($ad['location']) ?>
                            </div>
                            
                            <div class="detail-label">Kategori</div>
                            <div class="detail-value">
                                <i class="fas fa-tag me-2"></i> <?= htmlspecialchars($ad['category_name']) ?>
                            </div>
                            
                            <div class="detail-label">Dilihat</div>
                            <div class="detail-value">
                                <i class="fas fa-eye me-2"></i> <?= number_format(rand(100, 1000), 0, ',', '.') ?>x
                            </div>
                            
                            <div class="detail-label">Tanggal Posting</div>
                            <div class="detail-value">
                                <i class="far fa-calendar-alt me-2"></i> <?= date('d M Y', strtotime($ad['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Keamanan Transaksi</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light p-2 rounded-circle me-3">
                                <i class="fas fa-shield-alt text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Jaminan Keamanan</div>
                                <small class="text-muted">Transaksi aman dan terpercaya</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-light p-2 rounded-circle me-3">
                                <i class="fas fa-comments text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Laporkan Iklan</div>
                                <small class="text-muted">Jika ada masalah dengan iklan ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small> 2023 OLXClone. Hak Cipta Dilindungi.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>