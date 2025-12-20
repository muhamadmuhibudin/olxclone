<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
            padding-bottom: 15px;
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
        .btn-publish {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-preview {
            background-color: #f8f9fa;
            color: var(--primary-color);
            font-weight: 600;
            padding: 10px 30px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .btn-publish:hover {
            background-color: #002329;
            color: white;
        }
        .btn-preview:hover {
            background-color: #e9ecef;
        }
        .dropzone {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .dropzone .dz-message {
            margin: 2em 0;
        }
        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        .step-number {
            width: 30px;
            height: 30px;
            background-color: #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: #6c757d;
        }
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        .step.complete .step-number {
            background-color: #28a745;
            color: white;
        }
        .step-title {
            font-size: 14px;
            color: #6c757d;
        }
        .step.active .step-title {
            color: var(--primary-color);
            font-weight: 600;
        }
        .step-line {
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #dee2e6;
            z-index: -1;
        }
        .step-line-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: var(--primary-color);
            width: 0%;
            transition: width 0.3s;
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
                        <a class="nav-link" href="profile.php"><i class="fas fa-user me-1"></i> Profil Saya</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Post Ad Form -->
    <div class="container mb-5">
        <div class="post-ad-container">
            <div class="post-ad-header">
                <h2>Pasang Iklan Baru</h2>
                <p class="text-muted">Lengkapi informasi iklan Anda dengan detail yang jelas</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step1">
                    <div class="step-number">1</div>
                    <div class="step-title">Informasi Dasar</div>
                </div>
                <div class="step" id="step2">
                    <div class="step-number">2</div>
                    <div class="step-title">Unggah Foto</div>
                </div>
                <div class="step" id="step3">
                    <div class="step-number">3</div>
                    <div class="step-title">Detail</div>
                </div>
                <div class="step" id="step4">
                    <div class="step-number">4</div>
                    <div class="step-title">Pratinjau</div>
                </div>
                <div class="step-line">
                    <div class="step-line-progress" id="stepProgress"></div>
                </div>
            </div>

            <!-- Form Steps -->
            <form id="postAdForm" action="process_post_ad.php" method="POST" enctype="multipart/form-data">
                <!-- Step 1: Basic Information -->
                <div class="step-content" id="step1-content">
                    <h5 class="mb-4">Informasi Dasar</h5>
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <option value="elektronik">Elektronik</option>
                            <option value="kendaraan">Kendaraan</option>
                            <option value="properti">Properti</option>
                            <option value="fashion">Fashion</option>
                            <option value="hobi">Hobi & Olahraga</option>
                            <option value="rumah-tangga">Rumah Tangga</option>
                            <option value="jasa">Jasa</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Iklan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: iPhone 12 Pro Max 256GB Garansi Resmi" required>
                        <div class="form-text">Maksimal 60 karakter</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Jelaskan barang/jasa yang Anda tawarkan secara detail"></textarea>
                        <div class="form-text">Minimal 30 karakter</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="price" name="price" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="condition" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select" id="condition" name="condition" required>
                                <option value="new">Baru</option>
                                <option value="used" selected>Bekas</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div></div>
                        <button type="button" class="btn btn-preview" id="nextToStep2">Selanjutnya</button>
                    </div>
                </div>

                <!-- Step 2: Upload Photos -->
                <div class="step-content d-none" id="step2-content">
                    <h5 class="mb-4">Unggah Foto</h5>
                    <div class="mb-3">
                        <div id="dropzone" class="dropzone">
                            <div class="dz-message" data-dz-message>
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #6c757d;"></i>
                                <h5>Seret dan lepas foto di sini</h5>
                                <p class="text-muted">atau klik untuk memilih file</p>
                            </div>
                        </div>
                        <div id="preview-container" class="d-flex flex-wrap"></div>
                        <div class="form-text">Unggah minimal 1 foto (maks. 10 foto). Format: JPG, PNG. Maks. 5MB per foto.</div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-preview" id="backToStep1">Kembali</button>
                        <button type="button" class="btn btn-preview" id="nextToStep3">Selanjutnya</button>
                    </div>
                </div>

                <!-- Step 3: Additional Details -->
                <div class="step-content d-none" id="step3-content">
                    <h5 class="mb-4">Detail Tambahan</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <select class="form-select" id="location" name="location" required>
                                <option value="" selected disabled>Pilih Lokasi</option>
                                <option value="jakarta">Jakarta</option>
                                <option value="bandung">Bandung</option>
                                <option value="surabaya">Surabaya</option>
                                <option value="medan">Medan</option>
                                <option value="semarang">Semarang</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Penjual</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="seller_type" id="personal" value="personal" checked>
                            <label class="form-check-label" for="personal">
                                Perorangan
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="seller_type" id="business" value="business">
                            <label class="form-check-label" for="business">
                                Bisnis (Toko/Penjual Resmi)
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="businessNameContainer" style="display: none;">
                        <label for="businessName" class="form-label">Nama Toko/Bisnis</label>
                        <input type="text" class="form-control" id="businessName" name="business_name">
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-preview" id="backToStep2">Kembali</button>
                        <button type="button" class="btn btn-preview" id="nextToStep4">Selanjutnya</button>
                    </div>
                </div>

                <!-- Step 4: Preview -->
                <div class="step-content d-none" id="step4-content">
                    <h5 class="mb-4">Pratinjau Iklan Anda</h5>
                    <div class="card mb-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x200?text=Preview+Gambar" class="img-fluid rounded-start" alt="Preview Iklan" id="previewImage">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title" id="previewTitle">Judul Iklan</h5>
                                    <h4 class="text-primary mb-3" id="previewPrice">Rp 0</h4>
                                    <p class="card-text text-muted" id="previewLocation"><i class="fas fa-map-marker-alt me-2"></i>Lokasi</p>
                                    <p class="card-text" id="previewDescription">Deskripsi iklan akan muncul di sini</p>
                                    <p class="card-text"><small class="text-muted" id="previewDate">Diposting hari ini</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-preview" id="backToStep3">Kembali</button>
                        <button type="submit" class="btn btn-publish">Pasang Iklan</button>
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
        // Initialize Dropzone
        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("#dropzone", {
            url: "/file-upload",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            maxFilesize: 5, // MB
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            dictDefaultMessage: "",
            dictRemoveFile: "Hapus",
            dictFileTooBig: "Ukuran file terlalu besar (maks. 5MB)",
            dictInvalidFileType: "Format file tidak didukung",
            dictMaxFilesExceeded: "Anda tidak dapat mengunggah lebih dari 10 file"
        });

        // Handle file upload preview
        myDropzone.on("addedfile", function(file) {
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.createElement('img');
            previewImage.src = URL.createObjectURL(file);
            previewImage.className = 'preview-image';
            previewImage.alt = 'Preview';
            previewContainer.appendChild(previewImage);
        });

        // Handle file removal
        myDropzone.on("removedfile", function(file) {
            // Remove preview image
            const previews = document.querySelectorAll('.preview-image');
            previews.forEach(preview => {
                if (preview.src.includes(file.name)) {
                    preview.remove();
                }
            });
        });

        // Step navigation
        let currentStep = 1;
        const totalSteps = 4;

        function updateStepIndicator() {
            // Update step numbers
            for (let i = 1; i <= totalSteps; i++) {
                const step = document.getElementById(`step${i}`);
                if (i < currentStep) {
                    step.classList.remove('active');
                    step.classList.add('complete');
                } else if (i === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('complete');
                } else {
                    step.classList.remove('active', 'complete');
                }
            }

            // Update progress line
            const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
            document.getElementById('stepProgress').style.width = `${progress}%`;

            // Show current step content
            document.querySelectorAll('.step-content').forEach((content, index) => {
                if ((index + 1) === currentStep) {
                    content.classList.remove('d-none');
                } else {
                    content.classList.add('d-none');
                }
            });
        }

        // Next/Back buttons
        document.getElementById('nextToStep2').addEventListener('click', () => {
            // Basic validation for step 1
            const form = document.forms['postAdForm'];
            if (!form.title.value || !form.description.value || !form.price.value) {
                alert('Harap lengkapi semua field yang wajib diisi');
                return;
            }
            currentStep = 2;
            updateStepIndicator();
        });

        document.getElementById('nextToStep3').addEventListener('click', () => {
            // Check if at least one image is uploaded
            if (myDropzone.files.length === 0) {
                alert('Harap unggah minimal 1 foto');
                return;
            }
            currentStep = 3;
            updateStepIndicator();
        });

        document.getElementById('nextToStep4').addEventListener('click', () => {
            // Update preview
            const form = document.forms['postAdForm'];
            document.getElementById('previewTitle').textContent = form.title.value;
            document.getElementById('previewPrice').textContent = `Rp ${parseInt(form.price.value).toLocaleString('id-ID')}`;
            document.getElementById('previewDescription').textContent = form.description.value;
            document.getElementById('previewLocation').innerHTML = `<i class="fas fa-map-marker-alt me-2"></i>${form.location.options[form.location.selectedIndex].text}`;
            
            // Set preview image if available
            if (myDropzone.files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                };
                reader.readAsDataURL(myDropzone.files[0]);
            }

            currentStep = 4;
            updateStepIndicator();
        });

        document.getElementById('backToStep1').addEventListener('click', () => {
            currentStep = 1;
            updateStepIndicator();
        });

        document.getElementById('backToStep2').addEventListener('click', () => {
            currentStep = 2;
            updateStepIndicator();
        });

        document.getElementById('backToStep3').addEventListener('click', () => {
            currentStep = 3;
            updateStepIndicator();
        });

        // Toggle business name field
        document.querySelectorAll('input[name="seller_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const businessNameContainer = document.getElementById('businessNameContainer');
                businessNameContainer.style.display = this.value === 'business' ? 'block' : 'none';
                if (this.value === 'business') {
                    document.getElementById('businessName').setAttribute('required', '');
                } else {
                    document.getElementById('businessName').removeAttribute('required');
                }
            });
        });

        // Initialize the form
        updateStepIndicator();
    </script>
</body>
</html>