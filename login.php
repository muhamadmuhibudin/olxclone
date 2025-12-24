<?php
require_once 'config.php';

// Initialize error message
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    // Basic validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Email tidak valid';
    } elseif (empty($password)) {
        $error_message = 'Password harus diisi';
    } else {
        try {
            // Prepare and execute query
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            // Verify user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;
                
                // Redirect to dashboard or home page
                header('Location: index.php');
                exit();
            } else {
                $error_message = 'Email atau password salah';
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error_message = 'Terjadi kesalahan. Silakan coba lagi nanti.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OLXClone</title>
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
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo h2 {
            color: var(--primary-color);
            font-weight: 700;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(35, 229, 219, 0.25);
        }
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #002329;
            color: white;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <!-- Login Form -->
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <h2>Masuk ke Akun Anda</h2>
                <p class="text-muted">Masukkan email dan password Anda untuk melanjutkan</p>
            </div>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form id="loginForm" method="POST" onsubmit="return handleLogin(event)">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                               placeholder="contoh@email.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="text-end mt-2">
                        <a href="#" class="text-decoration-none" style="color: #002f34; font-size: 0.9rem;">Lupa password?</a>
                    </div>
                </div>
                <button type="submit" class="btn btn-login mb-3">
                    <span id="loginButtonText">Masuk</span>
                    <span id="loginSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <div class="text-center">
                    <p class="text-muted">Atau masuk dengan</p>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <a href="#" class="btn btn-outline-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fab fa-google"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary" style="border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar Sekarang</a>
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
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Handle form submission with AJAX
        function handleLogin(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginSpinner = document.getElementById('loginSpinner');
            
            // Show loading state
            submitBtn.disabled = true;
            loginButtonText.textContent = 'Memproses...';
            loginSpinner.classList.remove('d-none');
            
            // Send form data
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }
                return response.text();
            })
            .then(html => {
                if (html) {
                    // Create a temporary div to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    
                    // Check for error messages
                    const errorAlert = tempDiv.querySelector('.alert-danger');
                    if (errorAlert) {
                        // Remove any existing alerts
                        const existingAlert = form.querySelector('.alert');
                        if (existingAlert) {
                            existingAlert.remove();
                        }
                        
                        // Insert the new alert
                        form.insertBefore(errorAlert, form.firstChild);
                        
                        // Scroll to the top of the form
                        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi nanti.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                loginButtonText.textContent = 'Masuk';
                loginSpinner.classList.add('d-none');
            });
            
            return false;
        }
    </script>
</body>
</html>