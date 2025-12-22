<?php
require_once 'config.php';

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'field_errors' => []
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['first_name'] . ' ' . $_POST['last_name']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name)) {
        $response['field_errors']['name'] = 'Nama lengkap harus diisi';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['field_errors']['email'] = 'Email tidak valid';
    }

    if (strlen($password) < 8) {
        $response['field_errors']['password'] = 'Password minimal 8 karakter';
    } elseif ($password !== $confirm_password) {
        $response['field_errors']['confirm_password'] = 'Konfirmasi password tidak cocok';
    }

    // If no validation errors, proceed with registration
    if (empty($response['field_errors'])) {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $response['field_errors']['email'] = 'Email sudah terdaftar';
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $pdo->prepare("
                    INSERT INTO users (name, email, password) 
                    VALUES (?, ?, ?)
                ");
                
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    // Registration successful
                    $response['status'] = 'success';
                    $response['message'] = 'Pendaftaran berhasil! Silakan login.';
                    $response['redirect'] = 'login.php';
                }
            }
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            $response['message'] = 'Terjadi kesalahan. Silakan coba lagi nanti.';
        }
    }
} else {
    $response['message'] = 'Permintaan tidak valid';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);