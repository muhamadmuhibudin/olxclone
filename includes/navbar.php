<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #002f34;">
    <style>
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand, .nav-link, .dropdown-item {
            color: #f8f9fa !important;  /* Light text color for better contrast */
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .nav-link {
            font-size: 1rem;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 4px;
        }
        .nav-link:hover, .nav-link:focus {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff !important;
        }
        .dropdown-menu {
            background-color: #002f34;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .dropdown-item {
            color: #f8f9fa !important;
        }
        .dropdown-item:hover, .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff !important;
        }
        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-success {
            background-color: #1cb0b8;
            border-color: #1cb0b8;
        }
        .btn-success:hover {
            background-color: #16989f;
            border-color: #16989f;
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
    <div class="container">
        <a class="navbar-brand" href="index.php">OLXClone</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="post-ad.php"><i class="fas fa-plus-circle me-1"></i> Pasang Iklan</a>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menu ketika user sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" href="my_ads.php"><i class="fas fa-tag me-1"></i> Iklan Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="fas fa-user-edit me-1"></i> Edit Profil</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Akun Saya') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>Edit Profil</a></li>
                            <li><a class="dropdown-item" href="my_ads.php"><i class="fas fa-tag me-2"></i>Iklan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- Menu ketika user belum login -->
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link px-3" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                    </li>
                    <li class="nav-item ms-1 d-flex align-items-center">
                        <a class="btn btn-success px-3" href="register.php"><i class="fas fa-user-plus me-1"></i> Daftar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
