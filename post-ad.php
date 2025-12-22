<?php
session_start();
// You'll need to add your database connection and authentication check here
// For example:
// require_once 'config/database.php';
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php');
//     exit();
// }
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
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(35, 229, 219, 0.25);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #002329;
        }
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .image-preview {
            width: 120px;
            height: 120px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .dropzone {
            border: 2px dashed #0084ff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .dropzone:hover {
            background-color: #f8f9fa;
        }
        .dropzone i {
            font-size: 48px;
            color: #0084ff;
            margin-bottom: 10px;
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
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Post Ad Form -->
    <div class="container mb-5">
        <div class="post-ad-container">
            <div class="post-ad-header">
                <h2><i class="fas fa-plus-circle me-2"></i>Pasang Iklan Baru</h2>
                <p class="text-muted">Lengkapi form di bawah ini untuk mempublikasikan iklan Anda</p>
            </div>
            
            <form id="adForm" action="process_ad.php" method="POST" enctype="multipart/form-data">
                <!-- Basic Information Section -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                    </h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Judul Iklan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" required 
                                   placeholder="Contoh: iPhone 13 Pro Max 256GB Garansi Resmi">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category_id" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <option value="1">Elektronik</option>
                                <option value="2">Kendaraan</option>
                                <option value="3">Properti</option>
                                <option value="4">Fashion</option>
                                <option value="5">Hobi & Olahraga</option>
                                <option value="6">Kesehatan</option>
                                <option value="7">Perlengkapan Rumah</option>
                                <option value="8">Jasa</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price" name="price" 
                                       placeholder="Contoh: 15000000" required>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="negotiable" name="negotiable">
                                <label class="form-check-label" for="negotiable">Harga bisa nego</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" 
                                  placeholder="Tuliskan deskripsi lengkap produk/jasa Anda" required></textarea>
                        <div class="form-text">Minimal 30 karakter. Cantumkan detail kondisi, spesifikasi, dan kelebihan produk.</div>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-images me-2"></i>Foto Iklan
                    </h5>
                    <p class="text-muted">Unggah minimal 1 foto (maks. 10 foto). Foto pertama akan menjadi foto utama.</p>
                    
                    <div class="dropzone" id="imageDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p class="mb-0">Seret dan lepas foto di sini atau klik untuk memilih</p>
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" style="display: none;">
                    </div>
                    
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <!-- Image previews will be added here -->
                    </div>
                </div>

                <!-- Location Section -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-map-marker-alt me-2"></i>Lokasi
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="province" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="" selected disabled>Pilih Provinsi</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Banten">Banten</option>
                                <option value="Bali">Bali</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   placeholder="Contoh: Jakarta Selatan" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="address" name="address" rows="2" 
                                  placeholder="Contoh: Jl. Sudirman No. 123, RT 01/RW 02"></textarea>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">
                        <i class="fas fa-phone-alt me-2"></i>Kontak
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_name" class="form-label">Nama Kontak <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                   value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" 
                                       placeholder="81234567890" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="mb-4">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya menyetujui <a href="#" class="text-decoration-none">Syarat dan Ketentuan</a> serta <a href="#" class="text-decoration-none">Kebijakan Privasi</a> OLXClone
                        </label>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-secondary me-md-2" onclick="window.history.back()">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Pasang Iklan
                        </button>
                    </div>
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
    <!-- Dropzone JS -->
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    
    <script>
        // Image upload and preview
        const imageInput = document.getElementById('imageInput');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const dropzone = document.getElementById('imageDropzone');
        const maxFiles = 10;
        let fileCount = 0;

        // Handle click on dropzone
        dropzone.addEventListener('click', () => {
            imageInput.click();
        });

        // Handle file selection
        imageInput.addEventListener('change', (e) => {
            const files = e.target.files;
            handleFiles(files);
        });

        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropzone.style.borderColor = '#002f34';
            dropzone.style.backgroundColor = '#f8f9fa';
        }

        function unhighlight() {
            dropzone.style.borderColor = '#0084ff';
            dropzone.style.backgroundColor = 'transparent';
        }

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        });

        function handleFiles(files) {
            if (fileCount + files.length > maxFiles) {
                alert(`Anda hanya dapat mengunggah maksimal ${maxFiles} foto.`);
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (function(aFile) {
                        return function(e) {
                            const preview = document.createElement('div');
                            preview.className = 'image-preview';
                            preview.innerHTML = `
                                <img src="${e.target.result}" alt="${aFile.name}">
                                <button type="button" class="remove-image" onclick="removeImage(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            imagePreviewContainer.appendChild(preview);
                            fileCount++;
                        };
                    })(file);
                    reader.readAsDataURL(file);
                }
            }
        }

        // Remove image
        window.removeImage = function(button) {
            const preview = button.closest('.image-preview');
            preview.remove();
            fileCount--;
        };

        // Form validation
        document.getElementById('adForm').addEventListener('submit', function(e) {
            if (fileCount === 0) {
                e.preventDefault();
                alert('Silakan unggah minimal 1 foto untuk iklan Anda.');
                return false;
            }
            return true;
        });
    </script>
</body>
</html>