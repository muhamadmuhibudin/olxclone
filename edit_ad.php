<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'config.php';

$user_id = $_SESSION['user_id'];
$id = intval($_GET['id'] ?? 0);

try {
    // Ambil data iklan menggunakan PDO
    $stmt = $pdo->prepare("SELECT * FROM ads WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $ad = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ad) {
        echo "<script>alert('Iklan tidak ditemukan atau Anda tidak berhak mengedit.');window.location='my_ads.php';</script>";
        exit;
    }

    // Proses update jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        // Mulai transaksi
        $pdo->beginTransaction();

        try {
            // Update data iklan
            $update_stmt = $pdo->prepare("UPDATE ads SET title = :title, category_id = :category_id, 
                                         price = :price, location = :location, description = :description 
                                         WHERE id = :id AND user_id = :user_id");
            
            $update_stmt->bindParam(':title', $title);
            $update_stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':price', $price);
            $update_stmt->bindParam(':location', $location);
            $update_stmt->bindParam(':description', $description);
            $update_stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $update_stmt->execute();

            // Jika ada upload gambar baru
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid().'.'.$ext;
                $upload_path = 'uploads/'.$filename;
                
                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Hapus gambar lama (opsional)
                    $img_stmt = $pdo->prepare("SELECT image_path FROM ad_images WHERE ad_id = :ad_id LIMIT 1");
                    $img_stmt->bindParam(':ad_id', $id, PDO::PARAM_INT);
                    $img_stmt->execute();
                    $old_img = $img_stmt->fetch(PDO::FETCH_COLUMN);
                    
                    if($old_img && file_exists('uploads/'.$old_img)) {
                        unlink('uploads/'.$old_img);
                    }
                    
                    // Update gambar
                    $update_img_stmt = $pdo->prepare("UPDATE ad_images SET image_path = :image_path WHERE ad_id = :ad_id");
                    $update_img_stmt->bindParam(':image_path', $filename);
                    $update_img_stmt->bindParam(':ad_id', $id, PDO::PARAM_INT);
                    $update_img_stmt->execute();
                }
            }

            // Commit transaksi
            $pdo->commit();
            echo "<script>alert('Iklan berhasil diupdate!');window.location='my_ads.php';</script>";
            exit;

        } catch(PDOException $e) {
            // Rollback jika terjadi error
            $pdo->rollBack();
            echo "<script>alert('Gagal mengupdate iklan: ".addslashes($e->getMessage())."');</script>";
        }
    }

    // Ambil kategori
    $cats_stmt =$pdo->query("SELECT * FROM categories");
    $categories = $cats_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "<script>alert('Terjadi kesalahan: ".addslashes($e->getMessage())."');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Iklan - OLX Clone</title>
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="assets/css/style.css">
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

.navbar {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.card-header {
    background-color: white;
    border-bottom: 1px solid rgba(0,0,0,.125);
    font-weight: 600;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: 0.5rem 1.5rem;
}

.btn-primary:hover {
    background-color: #001f23;
    border-color: #001f23;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(0, 47, 52, 0.25);
}

.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    max-width: 100%;
    height: auto;
}
</style>
</head>
<body>
<header>
    <!-- Navbar -->
<?php include 'includes/navbar.php'; ?>

</header>
<section class="form-section">
    <div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Iklan</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <!-- Current Image -->
                            <?php if (!empty($ad['image_path'])): ?>
                            <div class="col-12">
                                <label class="form-label">Gambar Saat Ini:</label><br>
                                <img src="uploads/<?= htmlspecialchars($ad['image_path']) ?>" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                            <?php endif; ?>

                            <!-- Title -->
                            <div class="col-md-12">
                                <label class="form-label">Judul Iklan</label>
                                <input type="text" 
                                       name="title" 
                                       class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($ad['title']) ?>" 
                                       required>
                            </div>

                            <!-- Category and Price -->
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" 
                                                <?= $ad['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" 
                                       name="price" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($ad['price']) ?>" 
                                       required>
                            </div>

                            <!-- Location -->
                            <div class="col-12">
                                <label class="form-label">Lokasi</label>
                                <input type="text" 
                                       name="location" 
                                       class="form-control" 
                                       value="<?= htmlspecialchars($ad['location']) ?>" 
                                       required>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" 
                                          class="form-control" 
                                          rows="5" 
                                          required><?= htmlspecialchars($ad['description']) ?></textarea>
                            </div>

                            <!-- New Image -->
                            <div class="col-12">
                                <label class="form-label">Ganti Gambar (Opsional)</label>
                                <input type="file" 
                                       name="image" 
                                       class="form-control" 
                                       accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                            </div>
                            <!-- Move this inside the form, before the file input -->
<?php if (!empty($ad['image_path'])): ?>
<div class="mb-3">
    <label class="form-label">Gambar Saat Ini:</label><br>
    <img src="uploads/<?= htmlspecialchars($ad['image_path']) ?>" 
         class="img-thumbnail" 
         style="max-width: 200px; max-height: 200px; object-fit: cover;">
</div>
<?php endif; ?>

                            <!-- Submit Button -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-save me-2"></i>Update Iklan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
</body>
</html>