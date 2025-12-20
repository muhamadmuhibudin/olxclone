<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - OLXClone</title>
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
            background-color: #f8f9fa;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
            font-size: 1.8rem;
        }
        
        .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-success:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .product-image-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        
        .product-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .product-thumbnail.active {
            border-color: var(--primary-color);
        }
        
        .product-details {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .product-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 15px 0;
        }
        
        .product-info {
            margin: 20px 0;
            padding: 15px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .info-label {
            width: 120px;
            color: #6c757d;
        }
        
        .info-value {
            flex: 1;
            font-weight: 500;
        }
        
        .seller-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .seller-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .btn-chat {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #002F34;
            font-weight: 600;
        }
        
        .btn-chat:hover {
            background-color: #e9ecef;
        }
        
        .similar-products h4 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 30px 0 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .product-card-body {
            padding: 15px;
        }
        
        .product-card-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 48px;
        }
        
        .product-card-price {
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .product-card-location {
            font-size: 0.8rem;
            color: #6c757d;
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
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Cari di OLXClone" aria-label="Search">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-tag me-1"></i> Iklan Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user-circle me-1"></i> Akun Saya</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="#" class="btn btn-success"><i class="fas fa-plus-circle me-1"></i> Pasang Iklan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Mobil</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-8">
                <div class="product-image-container">
                    <div class="text-center mb-4">
                        <img src="https://placehold.co/800x600/00AA5B/FFFFFF?text=Produk+Image" alt="Produk" class="product-image" id="mainImage">
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <img src="https://placehold.co/600x400" alt="Thumbnail 1" class="product-thumbnail active" onclick="changeImage(this, 'https://placehold.co/800x600/00AA5B/FFFFFF?text=Produk+Image')">
                        <img src="https://placehold.co/600x400" alt="Thumbnail 2" class="product-thumbnail" onclick="changeImage(this, 'https://placehold.co/800x600/00AA5B/CCCCCC?text=Produk+Image+2')">
                        <img src="https://placehold.co/600x400" alt="Thumbnail 3" class="product-thumbnail" onclick="changeImage(this, 'https://placehold.co/800x600/00AA5B/DDDDDD?text=Produk+Image+3')">
                        <img src="https://placehold.co/600x400" alt="Thumbnail 4" class="product-thumbnail" onclick="changeImage(this, 'https://placehold.co/800x600/00AA5B/EEEEEE?text=Produk+Image+4')">
                    </div>
                </div>

                <!-- Product Description -->
                <div class="product-details">
                    <h2 class="product-title">Honda Jazz RS 2017 Matic</h2>
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-2"><i class="fas fa-map-marker-alt text-muted"></i></span>
                        <span class="text-muted">Jakarta Selatan, DKI Jakarta</span>
                        <span class="mx-2">•</span>
                        <span class="text-muted">Kemarin 15:30</span>
                        <span class="mx-2">•</span>
                        <span class="text-success">Aktif</span>
                    </div>
                    
                    <div class="product-price">Rp 215.000.000</div>
                    
                    <div class="product-info">
                        <div class="info-row">
                            <div class="info-label">Kondisi</div>
                            <div class="info-value">Bekas</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tahun</div>
                            <div class="info-value">2017</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Kilometer</div>
                            <div class="info-value">45.000 km</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tipe Bahan Bakar</div>
                            <div class="info-value">Bensin</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Transmisi</div>
                            <div class="info-value">Matic</div>
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Deskripsi</h5>
                    <p>
                        Dijual Honda Jazz RS 2017 warna putih, kondisi mulus, mesin halus, AC dingin, surat lengkap, pajak panjang, bebas banjir, bebas kecelakaan. 
                        <br><br>
                        Spesifikasi:
                        <br>
                        - Warna Putih
                        <br>
                        - Kilometer 45.000 km
                        <br>
                        - Transmisi Matic
                        <br>
                        - Pajak panjang
                        <br>
                        - Buku servis lengkap
                        <br>
                        - Ban baru
                        <br>
                        - Velg racing
                        <br><br>
                        Harga nego tipis. Untuk info lebih lanjut bisa langsung hubungi nomor yang tertera.
                    </p>
                </div>
            </div>
            
            <!-- Seller Info & Action -->
            <div class="col-lg-4">
                <div class="seller-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://placehold.co/100x100/CCCCCC/666666?text=User" alt="Seller" class="seller-avatar me-3">
                        <div>
                            <h5 class="mb-0">John Doe</h5>
                            <span class="text-muted">Member sejak Jan 2020</span>
                        </div>
                    </div>
                    <button class="btn btn-chat w-100 mb-2">
                        <i class="far fa-comment-dots me-2"></i> Chat Penjual
                    </button>
                    <button class="btn btn-success w-100">
                        <i class="fas fa-phone-alt me-2"></i> Tampilkan Nomor
                    </button>
                    
                    <div class="mt-4">
                        <h6 class="mb-3">Laporkan Iklan</h6>
                        <p class="small text-muted">Apakah iklan ini melanggar Ketentuan Penggunaan OLXClone? Beri tahu kami apa yang salah dengan iklan ini.</p>
                        <a href="#" class="text-decoration-none">Laporkan <i class="fas fa-chevron-right ms-1 small"></i></a>
                    </div>
                </div>
                
                <div class="safety-tips mt-4 p-3 bg-light rounded">
                    <h6><i class="fas fa-shield-alt text-success me-2"></i> Tips Aman</h6>
                    <ul class="small">
                        <li>Jangan melakukan pembayaran di muka</li>
                        <li>Bertemu penjual di tempat umum yang ramai</li>
                        <li>Periksa kondisi barang sebelum membeli</li>
                        <li>Gunakan OLX Chat untuk berkomunikasi</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Similar Products -->
        <div class="similar-products">
            <h4>Iklan serupa di dekat Anda</h4>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-6 col-md-3">
                    <div class="product-card">
                        <img src="https://placehold.co/300x200/00AA5B/FFFFFF?text=Honda+Jazz" alt="Honda Jazz">
                        <div class="product-card-body">
                            <h5 class="product-card-title">Honda Jazz RS 2018 Matic</h5>
                            <div class="product-card-price">Rp 235.000.000</div>
                            <div class="product-card-location">
                                <i class="fas fa-map-marker-alt me-1"></i> Jakarta Selatan
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="col-6 col-md-3">
                    <div class="product-card">
                        <img src="https://placehold.co/300x200/00AA5B/FFFFFF?text=Toyota+Yaris" alt="Toyota Yaris">
                        <div class="product-card-body">
                            <h5 class="product-card-title">Toyota Yaris 2019 Matic</h5>
                            <div class="product-card-price">Rp 245.000.000</div>
                            <div class="product-card-location">
                                <i class="fas fa-map-marker-alt me-1"></i> Jakarta Barat
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="col-6 col-md-3">
                    <div class="product-card">
                        <img src="https://placehold.co/300x200/00AA5B/FFFFFF?text=Daihatsu+Xenia" alt="Daihatsu Xenia">
                        <div class="product-card-body">
                            <h5 class="product-card-title">Daihatsu Xenia 2020 Matic</h5>
                            <div class="product-card-price">Rp 195.000.000</div>
                            <div class="product-card-location">
                                <i class="fas fa-map-marker-alt me-1"></i> Tangerang
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="col-6 col-md-3">
                    <div class="product-card">
                        <img src="https://placehold.co/300x200/00AA5B/FFFFFF?text=Honda+Mobilio" alt="Honda Mobilio">
                        <div class="product-card-body">
                            <h5 class="product-card-title">Honda Mobilio RS 2017 Matic</h5>
                            <div class="product-card-price">Rp 205.000.000</div>
                            <div class="product-card-location">
                                <i class="fas fa-map-marker-alt me-1"></i> Depok
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5>OLXClone</h5>
                    <p class="text-muted">Jual beli aman dan nyaman hanya di OLXClone</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>BANTUAN</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#" class="text-muted">Cara Berjualan</a></li>
                        <li><a href="#" class="text-muted">Syarat dan Ketentuan</a></li>
                        <li><a href="#" class="text-muted">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-muted">Pusat Bantuan</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>TENTANG KAMI</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#" class="text-muted">Tentang OLXClone</a></li>
                        <li><a href="#" class="text-muted">Karir</a></li>
                        <li><a href="#" class="text-muted">Blog</a></li>
                        <li><a href="#" class="text-muted">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>IKUTI KAMI</h6>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="mt-3">
                        <img src="https://www.olx.co.id/assets/iconGooglePlayEN_noinline.2f29b0aed0a2c5a7d886c1b2e4b4a8f2.webp" alt="Google Play" style="height: 40px;" class="me-2">
                        <img src="https://www.olx.co.id/assets/iconAppStoreEN_noinline.9f2090465d8c5c833b10edcf3d8bc698.webp" alt="App Store" style="height: 40px;">
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center text-muted">
                    <p class="mb-0">© 2025 OLXClone - All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeImage(element, newImageSrc) {
            // Remove active class from all thumbnails
            document.querySelectorAll('.product-thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            element.classList.add('active');
            
            // Change main image
            document.getElementById('mainImage').src = newImageSrc;
        }
    </script>
</body>
</html>