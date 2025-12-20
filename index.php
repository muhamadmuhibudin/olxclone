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
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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

    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 fw-bold mb-4">Jual Beli Online Aman dan Nyaman Hanya di OLXClone</h1>
                    <div class="search-box">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" placeholder="Apa yang kamu cari?" aria-label="Search">
                            <select class="form-select" style="max-width: 200px;">
                                <option selected>Semua Kategori</option>
                                <option>Properti</option>
                                <option>Kendaraan</option>
                                <option>Elektronik</option>
                                <option>Hobi & Olahraga</option>
                            </select>
                            <button class="btn btn-success" type="button"><i class="fas fa-search me-2"></i>Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories py-5">
        <div class="container">
            <h4 class="mb-4">Kategori Populer</h4>
            <div class="row">
                <div class="col-6 col-md-3 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h5>Kendaraan</h5>
                        <p class="text-muted">Mobil, Motor, dll</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5>Handphone</h5>
                        <p class="text-muted">HP, Tablet, dll</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h5>Properti</h5>
                        <p class="text-muted">Rumah, Tanah, dll</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <h5>Elektronik</h5>
                        <p class="text-muted">Laptop, TV, dll</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Iklan Terbaru</h4>
                <a href="#" class="text-decoration-none">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="row">
                <!-- Product Card 1 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="product-card">
                        <img src="https://placehold.co/600x400" alt="Product" class="product-image">
                        <div class="p-3">
                            <h6 class="mb-1">iPhone 13 Pro Max 256GB Garansi Resmi</h6>
                            <p class="product-price mb-1">Rp 15.500.000</p>
                            <p class="location-text mb-0"><i class="fas fa-map-marker-alt me-1"></i> Jakarta Selatan</p>
                            <p class="text-muted small mb-0">Hari ini 10:00</p>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 2 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="product-card">
                        <img src="https://placehold.co/600x400" alt="Product" class="product-image">
                        <div class="p-3">
                            <h6 class="mb-1">Honda Beat 2022 Mulus Surat Lengkap</h6>
                            <p class="product-price mb-1">Rp 18.500.000</p>
                            <p class="location-text mb-0"><i class="fas fa-map-marker-alt me-1"></i> Bandung</p>
                            <p class="text-muted small mb-0">Kemarin 15:30</p>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 3 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="product-card">
                        <img src="https://placehold.co/600x400" alt="Product" class="product-image">
                        <div class="p-3">
                            <h6 class="mb-1">Rumah 2 Lantai di Bintaro Tangerang</h6>
                            <p class="product-price mb-1">Rp 1.250.000.000</p>
                            <p class="location-text mb-0"><i class="fas fa-map-marker-alt me-1"></i> Tangerang Selatan</p>
                            <p class="text-muted small mb-0">2 hari yang lalu</p>
                        </div>
                    </div>
                </div>
                
                <!-- Product Card 4 -->
                <div class="col-6 col-md-3 mb-4">
                    <div class="product-card">
                        <img src="https://placehold.co/600x400" alt="Product" class="product-image">
                        <div class="p-3">
                            <h6 class="mb-1">MacBook Pro M1 2020 16GB/512GB</h6>
                            <p class="product-price mb-1">Rp 22.500.000</p>
                            <p class="location-text mb-0"><i class="fas fa-map-marker-alt me-1"></i> Surabaya</p>
                            <p class="text-muted small mb-0">3 hari yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <!-- Categories Section -->
<section class="popular-categories py-5">
    <div class="container">
        <h4 class="mb-4">Telusuri Kategori</h4>
        <div class="row g-3 justify-content-center">
            <!-- Category 1 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-car fa-2x text-primary"></i>
                        </div>
                        <div>Mobil</div>
                    </div>
                </a>
            </div>
            <!-- Category 2 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-motorcycle fa-2x text-primary"></i>
                        </div>
                        <div>Motor</div>
                    </div>
                </a>
            </div>
            <!-- Category 3 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                        </div>
                        <div>Handphone</div>
                    </div>
                </a>
            </div>
            <!-- Category 4 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-laptop fa-2x text-primary"></i>
                        </div>
                        <div>Laptop</div>
                    </div>
                </a>
            </div>
            <!-- Category 5 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home fa-2x text-primary"></i>
                        </div>
                        <div>Properti</div>
                    </div>
                </a>
            </div>
            <!-- Category 6 -->
            <div class="col-6 col-sm-4 col-md-2 mb-3 d-flex">
                <a href="#" class="text-decoration-none text-dark w-100">
                    <div class="text-center p-3 bg-white rounded h-100 d-flex flex-column">
                        <div class="mb-2 flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tshirt fa-2x text-primary"></i>
                        </div>
                        <div>Fashion</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

    <!-- Mobile App Section -->
<section class="mobile-app py-5 bg-light">
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
    <footer>
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
                            <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; 2025 OLXClone. Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
