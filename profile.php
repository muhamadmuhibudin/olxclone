<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$error_message = '';
$success_message = '';

// Fetch current user data
$stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Basic validation
    if (empty($name)) {
        $error_message = 'Nama tidak boleh kosong';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Email tidak valid';
    } else {
        try {
            // Check if email is already taken by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $user['id']]);
            if ($stmt->fetch()) {
                $error_message = 'Email sudah digunakan oleh akun lain';
            } else {
                $update_data = [':name' => $name, ':email' => $email, ':id' => $user['id']];
                $password_updated = false;
                
                // Check if user wants to change password
                if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
                    // Verify current password
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $current_user = $stmt->fetch();
                    
                    if (!password_verify($current_password, $current_user['password'])) {
                        $error_message = 'Password saat ini salah';
                    } elseif (strlen($new_password) < 6) {
                        $error_message = 'Password baru minimal 6 karakter';
                    } elseif ($new_password !== $confirm_password) {
                        $error_message = 'Konfirmasi password tidak cocok';
                    } else {
                        $update_data[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
                        $password_updated = true;
                    }
                }
                
                if (empty($error_message)) {
                    // Update user data
                    $sql = "UPDATE users SET name = :name, email = :email" . 
                           ($password_updated ? ", password = :password" : "") . 
                           " WHERE id = :id";
                    
                    $stmt = $pdo->prepare($sql);
                    $result = $stmt->execute($update_data);
                    
                    if ($result) {
                        // Update session
                        $_SESSION['user_name'] = $name;
                        $_SESSION['user_email'] = $email;
                        $success_message = 'Profil berhasil diperbarui';
                        
                        // Refresh user data
                        $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        $user = $stmt->fetch();
                    } else {
                        $error_message = 'Gagal memperbarui profil. Silakan coba lagi.';
                    }
                }
            }
        } catch (PDOException $e) {
            error_log("Profile Update Error: " . $e->getMessage());
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
    <title>Edit Profil - OLXClone</title>
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
        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .profile-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .profile-content {
            padding: 30px;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(35, 229, 219, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #001f23;
            border-color: #001f23;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .alert {
            border-radius: 8px;
        }
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .tab-content {
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<?php include 'includes/navbar.php'; ?>

    <div class="container py-5">
        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <div class="profile-header">
                <h1><i class="fas fa-user-edit me-2"></i>Edit Profil</h1>
            </div>
            <div class="profile-content">
                <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="fas fa-user-circle me-2"></i><h7 class="mb-4" style="color: #000;">Ubah Profil</h7>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                            <i class="fas fa-lock me-2"></i><h7 class="mb-4" style="color: #000;">Ubah Password</h7>
                        </button>
                    </li>
                </ul>
<!-- tempat tab konten -->
                <div class="tab-content" id="profileTabsContent">
<!-- Profile Information Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <form method="POST" action="profile.php">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

                    
<!-- Change Password Tab -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form method="POST" action="profile.php">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                            
                            <div class="form-group">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    <span class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="new_password" name="new_password" minlength="6">
                                    <span class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                    <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
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
                    <p class="mb-0">Temukan berbagai produk terbaik di OLXClone. Jual beli aman dan nyaman hanya di OLXClone.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Beranda</a></li>
                        <li><a href="#" class="text-white">Tentang Kami</a></li>
                        <li><a href="#" class="text-white">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-white">Syarat dan Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> info@olxclone.com</li>
                        <li><i class="fas fa-phone me-2"></i> +62 123 4567 890</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> OLXClone. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.querySelector(`#${inputId} + .password-toggle i`);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const newPassword = form.querySelector('input[name="new_password"]');
                    const confirmPassword = form.querySelector('input[name="confirm_password"]');
                    
                    if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
                        e.preventDefault();
                        alert('Konfirmasi password tidak cocok');
                        return false;
                    }
                    
                    if (newPassword && newPassword.value.length > 0 && newPassword.value.length < 6) {
                        e.preventDefault();
                        alert('Password baru minimal 6 karakter');
                        return false;
                    }
                    
                    return true;
                });
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
