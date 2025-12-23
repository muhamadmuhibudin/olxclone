<?php
require_once 'config.php';

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'field_errors' => []
];

function formatWhatsAppNumber($number) {
    // Remove any non-digit characters
    $number = preg_replace('/[^0-9]/', '', $number);
    
    // Handle different number formats
    if (strpos($number, '0') === 0) {
        // If number starts with 0, replace with +62
        $number = '+62' . substr($number, 1);
    } elseif (strpos($number, '62') === 0) {
        // If number starts with 62, add +
        $number = '+' . $number;
    } elseif (strpos($number, '+62') !== 0) {
        // If number doesn't start with +62, add it
        $number = '+62' . $number;
    }
    
    return $number;
}
// In the main code, after getting the form data, add:
$whatsapp = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '';
// Add validation for WhatsApp number
if (empty($whatsapp)) {
    $response['field_errors']['whatsapp'] = 'Nomor WhatsApp harus diisi';
} elseif (!preg_match('/^(\+62|62|0)[0-9]{8,15}$/', $whatsapp)) {
    $response['field_errors']['whatsapp'] = 'Format nomor WhatsApp tidak valid';
} else {
    // Format the WhatsApp number
    $whatsapp = formatWhatsAppNumber($whatsapp);
}
// In the database insert query, include the whatsapp field:
$stmt = $pdo->prepare("
    INSERT INTO users (name, email, password, whatsapp) 
    VALUES (?, ?, ?, ?)
");
// And update the execute statement:
if ($stmt->execute([$name, $email, $hashed_password, $whatsapp])) {
    // Registration successful
    $response['status'] = 'success';
    $response['message'] = 'Pendaftaran berhasil! Silakan login.';
    $response['redirect'] = 'login.php';
}

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