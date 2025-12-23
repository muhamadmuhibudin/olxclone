<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$error_message = '';
$success_message = '';

// Get categories for dropdown
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching categories: " . $e->getMessage());
    $error_message = 'Terjadi kesalahan. Silakan coba lagi nanti.';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = trim($_POST['title'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $price = (float)str_replace(['.', ','], ['', '.'], $_POST['price'] ?? '0');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $user_id = $_SESSION['user_id'];
    
    // Validate input
    $errors = [];
    
    if (empty($title)) {
        $errors['title'] = 'Judul iklan harus diisi';
    } elseif (strlen($title) > 150) {
        $errors['title'] = 'Judul maksimal 150 karakter';
    }
    
    if ($category_id <= 0) {
        $errors['category_id'] = 'Pilih kategori yang sesuai';
    }
    
    if ($price <= 0) {
        $errors['price'] = 'Harga harus lebih dari 0';
    }
    
    if (empty($description)) {
        $errors['description'] = 'Deskripsi harus diisi';
    }
    
    if (empty($location)) {
        $errors['location'] = 'Lokasi harus diisi';
    }
    
    // Handle image uploads
    $uploaded_images = [];
    if (isset($_FILES['images'])) {
        $upload_dir = 'uploads/ads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $file_name = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
                $target_path = $upload_dir . $file_name;
                
                // Validate image type
                $file_type = mime_content_type($tmp_name);
                if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif'])) {
                    if (move_uploaded_file($tmp_name, $target_path)) {
                        $uploaded_images[] = $target_path;
                    }
                }
            }
        }
        
        if (empty($uploaded_images)) {
            $errors['images'] = 'Minimal unggah satu gambar';
        }
    } else {
        $errors['images'] = 'Minimal unggah satu gambar';
    }
    
    // If no validation errors, save to database
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Insert ad
            $stmt = $pdo->prepare("
                INSERT INTO ads (user_id, category_id, title, description, price, location)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $category_id, $title, $description, $price, $location]);
            $ad_id = $pdo->lastInsertId();
            
            // Save images
            $stmt = $pdo->prepare("
                INSERT INTO ad_images (ad_id, image_path)
                VALUES (?, ?)
            ");
            
            foreach ($uploaded_images as $image_path) {
                $stmt->execute([$ad_id, $image_path]);
            }

            // Commit transaction
            $pdo->commit();
            
            $success_message = 'Iklan berhasil diposting!';
            // Clear form
            $_POST = [];
            
        } catch (PDOException $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            error_log("Error saving ad: " . $e->getMessage());
            $error_message = 'Terjadi kesalahan saat menyimpan iklan. Silakan coba lagi.';
            
            // Clean up uploaded files if any
            foreach ($uploaded_images as $image) {
                if (file_exists($image)) {
                    unlink($image);
                }
            }
        }
    } else {
        $error_message = 'Terdapat kesalahan pada form. Silakan periksa kembali.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasang Iklan - OLXClone</title>
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
        .post-ad-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .post-ad-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .post-ad-header h2 {
            color: var(--primary-color);
            font-weight: 700;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(35, 229, 219, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover {
            background-color: #002329;
            border-color: #002329;
        }
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .image-upload-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .image-upload-area:hover {
            border-color: var(--primary-color);
        }
        .price-input {
            position: relative;
        }
        .price-input span {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .price-input input {
            padding-left: 30px;
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
                        <a class="nav-link active" href="post-ad.php"><i class="fas fa-plus-circle me-1"></i> Pasang Iklan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php"><i class="fas fa-user me-1"></i> Profil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mb-5">
        <div class="post-ad-container">
            <div class="post-ad-header">
                <h2><i class="fas fa-plus-circle me-2"></i>Pasang Iklan Baru</h2>
                <p class="text-muted">Lengkapi form di bawah ini untuk mempublikasikan iklan Anda</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <?php if (!empty($errors)): ?>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                    <a href="ad.php?id=<?php echo $ad_id ?? ''; ?>" class="alert-link">Lihat iklan</a>
                </div>
            <?php endif; ?>

            <form id="adForm" method="POST" enctype="multipart/form-data">
                <!-- Basic Information Section -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Judul Iklan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" 
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" 
                                   required>
                            <?php if (isset($errors['title'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['title']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" 
                                    id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" 
                                        <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['category_id']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <div class="price-input">
                                <span>Rp</span>
                                <input type="text" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" 
                                       id="price" name="price" 
                                       value="<?php echo isset($_POST['price']) ? number_format((float)$_POST['price'], 0, ',', '.') : ''; ?>" 
                                       onkeyup="formatCurrency(this)" required>
                            </div>
                            <?php if (isset($errors['price'])): ?>
                                <div class="invalid-feedback d-block"><?php echo htmlspecialchars($errors['price']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" 
                                      id="description" name="description" rows="5" 
                                      required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?php echo htmlspecialchars($errors['description']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-images me-2"></i>Foto Iklan
                    </h5>
                    <div class="image-upload-area" onclick="document.getElementById('images').click();">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-2" style="color: var(--primary-color);"></i>
                        <p class="mb-1">Klik atau seret gambar ke sini</p>
                        <p class="text-muted small">Unggah minimal 1 foto (maks. 5 foto)</p>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" style="display: none;" onchange="previewImages(this);">
                    <div id="imagePreviewContainer" class="image-preview-container">
                        <!-- Image previews will be added here -->
                    </div>
                    <?php if (isset($errors['images'])): ?>
                        <div class="text-danger small"><?php echo htmlspecialchars($errors['images']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- Location Section -->

           <div class="mb-3">
    <label for="location" class="form-label">Lokasi</label>

    <select class="form-select <?php echo isset($errors['location']) ? 'is-invalid' : ''; ?>"
            id="location"
            name="location"
            required>

        <option value="">Pilih Lokasi</option>

        <?php foreach ($locations as $loc): ?>
            <option value="<?php echo htmlspecialchars($loc['name']); ?>"
                <?php echo (!empty($_POST['location']) && $_POST['location'] === $loc['name']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($loc['name']); ?>
            </option>
        <?php endforeach; ?>

    </select>

    <?php if (isset($errors['location'])): ?>
        <div class="invalid-feedback">
            <?php echo htmlspecialchars($errors['location']); ?>
        </div>
    <?php endif; ?>
</div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Pasang Iklan
                    </button>
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
        // Format currency input
        function formatCurrency(input) {
            // Remove non-numeric characters
            let value = input.value.replace(/\D/g, '');
            
            // Format with thousand separators
            if (value) {
                value = parseInt(value).toLocaleString('id-ID');
                input.value = value;
            }
        }

        // Image preview functionality
        function previewImages(input) {
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = '';
            
            if (input.files) {
                const files = Array.from(input.files).slice(0, 5); // Limit to 5 images
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'position-relative d-inline-block';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="image-preview" alt="Preview ${index + 1}">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" 
                                    onclick="removeImage(this, ${index})" style="width: 20px; height: 20px; padding: 0; line-height: 1;">
                                &times;
                            </button>
                        `;
                        container.appendChild(div);
                    }
                    
                    reader.readAsDataURL(file);
                });
            }
        }

        // Remove image from preview and file input
        function removeImage(button, index) {
            // Remove from preview
            button.closest('.position-relative').remove();
            
            // Remove from file input
            const input = document.getElementById('images');
            const files = Array.from(input.files);
            files.splice(index, 1);
            
            // Create new DataTransfer to update files
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        // Drag and drop functionality
        const dropArea = document.querySelector('.image-upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.style.borderColor = '#002f34';
            dropArea.style.backgroundColor = 'rgba(0, 47, 52, 0.05)';
        }

        function unhighlight() {
            dropArea.style.borderColor = '#ccc';
            dropArea.style.backgroundColor = '';
        }

        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const input = document.getElementById('images');
            
            // Update file input
            const dataTransfer = new DataTransfer();
            const existingFiles = Array.from(input.files);
            
            // Add existing files
            existingFiles.forEach(file => dataTransfer.items.add(file));
            
            // Add new files (up to 5 total)
            const remainingSlots = 5 - existingFiles.length;
            if (remainingSlots > 0) {
                const filesToAdd = Math.min(remainingSlots, files.length);
                for (let i = 0; i < filesToAdd; i++) {
                    dataTransfer.items.add(files[i]);
                }
                input.files = dataTransfer.files;
                
                // Trigger change event to update preview
                const event = new Event('change');
                input.dispatchEvent(event);
            } else {
                alert('Anda hanya dapat mengunggah maksimal 5 foto');
            }
        }

        // Form validation
        document.getElementById('adForm').addEventListener('submit', function(e) {
            const images = document.getElementById('images');
            if (images.files.length === 0) {
                e.preventDefault();
                alert('Silakan unggah minimal 1 foto');
                return false;
            }
            return true;
        });
    </script>
</body>
</html>