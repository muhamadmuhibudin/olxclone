<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - OLXClone</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        #togglePassword i,
        #toggleConfirmPassword i {
        color: #002f34;
        font-size: 16px;
        }
        .register-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .register-logo {
            text-align: center;
            margin-bottom: 25px;
        }
        .register-logo h2 {
            color: var(--primary-color);
            font-weight: 700;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(35, 229, 219, 0.25);
        }
        .btn-register {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background-color: #002329;
            color: white;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        /* Add this to your existing CSS */
.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
}

.input-group-text .fa-eye,
.input-group-text .fa-eye-slash {
    color: #6c757d;
    font-size: 1rem;
}

/* Ensure consistent height for input groups */
.input-group > .form-control,
.input-group > .input-group-text {
    height: calc(2.25rem + 2px);
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #002f34;">
        <div class="container">
            <a class="navbar-brand" href="index.php">OLXClone</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-heart me-1"></i> Favorit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-bell me-1"></i> Notifikasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-plus-circle me-1"></i> Pasang Iklan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <div class="container mb-5">
        <div class="register-container">
            <div class="register-logo">
                <h2>Daftar Akun Baru</h2>
                <p class="text-muted">Lengkapi data diri Anda untuk membuat akun</p>
            </div>
            <form action="process_register.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName" class="form-label">Nama Depan</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName" class="form-label">Nama Belakang</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                </div>
                <div class="mb-3">
    <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
        <input type="tel" class="form-control" id="whatsapp" name="whatsapp" 
               pattern="^(\+62|62|0)[0-9]{8,15}$" 
               title="Masukkan nomor WhatsApp yang valid (contoh: 081234567890)" 
               placeholder="081234567890" required>
    </div>
    <div class="form-text">Contoh: 081234567890</div>
</div>
                <!-- Update the password input group -->
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" id="password" name="password" class="form-control password-input" required>
        <span class="input-group-text toggle-password" style="cursor:pointer">
            <i class="fa-solid fa-eye"></i>
        </span>
    </div>
    <div class="form-text">Minimal 8 karakter, kombinasi huruf dan angka</div>
</div>

<!-- Update the confirm password input group -->
<div class="mb-3">
    <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
        <input type="password" id="confirmPassword" name="confirm_password" class="form-control confirm-password-input" required>
        <span class="input-group-text toggle-password" style="cursor:pointer">
            <i class="fa-solid fa-eye"></i>
        </span>
    </div>
</div>
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya setuju dengan <a href="#" class="text-decoration-none">Syarat dan Ketentuan</a> dan <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-register mb-3">Daftar</button>
                <div class="text-center">
                    <p class="text-muted">Atau daftar dengan</p>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <a href="#" class="btn btn-outline-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>
            </form>
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
                        <li><a href="#" class="text-muted text-decoration-none">Pusat Bantuan</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Cara Berjualan</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Kontak Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6>Ikuti Kami</h6>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
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
        document.addEventListener("DOMContentLoaded", function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Find the input element that's a sibling of the toggle button's parent
            const input = this.closest('.input-group').querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        });
    });

    // Password match validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');

    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Password tidak cocok");
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);

    // WhatsApp number formatting
    document.getElementById('whatsapp').addEventListener('input', function(e) {
        // Remove any non-digit characters
        let value = this.value.replace(/\D/g, '');
        
        // Format the number
        if (value.startsWith('62')) {
            value = '+' + value;
        } else if (!value.startsWith('0') && !value.startsWith('+62')) {
            value = '0' + value;
        }
        
        // Update the input value
        this.value = value;
    });

    // Form submission
    const registerForm = document.querySelector('form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Your existing form submission code
        });
    }
});

// whatsapp validation
document.getElementById('whatsapp').addEventListener('input', function(e) {
    // Remove any non-digit characters
    let value = this.value.replace(/\D/g, '');
    
    // If it starts with 0, keep it as is
    // If it starts with 62, change to +62
    // If it starts with +62, leave it
    if (value.startsWith('62')) {
        value = '+' + value;
    } else if (!value.startsWith('0') && !value.startsWith('+62')) {
        value = '0' + value;
    }
    
    // Update the input value
    this.value = value;
});

    
    // Form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous error messages
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            
            // Get form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('process_register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Show success message and redirect
                    alert(data.message);
                    window.location.href = data.redirect;
                } else {
                    // Display field errors
                    if (data.field_errors) {
                        Object.entries(data.field_errors).forEach(([field, message]) => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback';
                                errorDiv.textContent = message;
                                input.parentNode.insertBefore(errorDiv, input.nextSibling);
                            }
                        });
                    }
                    
                    // Show general error message
                    if (data.message) {
                        alert(data.message);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi nanti.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
});
    </script>
</body>
</html>