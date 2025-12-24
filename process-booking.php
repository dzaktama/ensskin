<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

// Ambil data dari form
$customer_name = trim($_POST['customer_name']);
$customer_phone = trim($_POST['customer_phone']);
$customer_email = trim($_POST['customer_email']);
$treatment_type = trim($_POST['treatment_type']);
$booking_date = $_POST['booking_date'];
$booking_time = $_POST['booking_time'];
$notes = trim($_POST['notes']);
$user_id = $_SESSION['user_id'] ?? null;

// Validasi data
$errors = [];

if (empty($customer_name)) {
    $errors[] = 'Nama harus diisi';
}
if (empty($customer_phone)) {
    $errors[] = 'No. WhatsApp harus diisi';
}
if (empty($treatment_type)) {
    $errors[] = 'Treatment harus dipilih';
}
if (empty($booking_date)) {
    $errors[] = 'Tanggal booking harus dipilih';
}
if (empty($booking_time)) {
    $errors[] = 'Waktu booking harus dipilih';
}

// Cek apakah tanggal tidak di masa lalu
if (strtotime($booking_date) < strtotime(date('Y-m-d'))) {
    $errors[] = 'Tanggal booking tidak boleh di masa lalu';
}

if (!empty($errors)) {
    $_SESSION['booking_error'] = implode(', ', $errors);
    header('Location: index.php#booking');
    exit;
}

// Insert ke database
$query = "INSERT INTO bookings (user_id, customer_name, customer_phone, customer_email, treatment_type, booking_date, booking_time, notes, status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

$stmt = executeQuery($query, [
    $user_id, 
    $customer_name, 
    $customer_phone, 
    $customer_email, 
    $treatment_type, 
    $booking_date, 
    $booking_time, 
    $notes
], "isssssss");

if ($stmt && $stmt->affected_rows > 0) {
    $booking_id = $conn->insert_id;
    
    // 1. Kirim ke Google Sheets (opsional)
    if (file_exists('config/sheets_api.php')) {
        require_once 'config/sheets_api.php';
        try {
            $sheets = new GoogleSheetsSync();
            $sheets->syncBooking([
                'customer_name' => $customer_name,
                'customer_phone' => $customer_phone,
                'customer_email' => $customer_email,
                'treatment_type' => $treatment_type,
                'booking_date' => $booking_date,
                'booking_time' => $booking_time,
                'notes' => $notes
            ]);
        } catch (Exception $e) {
            error_log("Sheets sync failed: " . $e->getMessage());
        }
    }
    
    // 2. Kirim notifikasi WhatsApp ke Admin
    $wa_admin = '6282112568941';
    $wa_message = "*BOOKING BARU - ENSSKIN*\n\n";
    $wa_message .= "ID Booking: #" . str_pad($booking_id, 5, '0', STR_PAD_LEFT) . "\n";
    $wa_message .= "Nama: " . $customer_name . "\n";
    $wa_message .= "No. HP: " . $customer_phone . "\n";
    $wa_message .= "Email: " . $customer_email . "\n";
    $wa_message .= "Treatment: " . $treatment_type . "\n";
    $wa_message .= "Tanggal: " . date('d M Y', strtotime($booking_date)) . "\n";
    $wa_message .= "Waktu: " . $booking_time . "\n";
    if (!empty($notes)) {
        $wa_message .= "Catatan: " . $notes . "\n";
    }
    $wa_message .= "\n_Segera konfirmasi booking ini_";
    
    // Redirect ke WhatsApp (bisa juga pakai API untuk kirim otomatis)
    $wa_url = "https://wa.me/" . $wa_admin . "?text=" . urlencode($wa_message);
    
    // 3. Kirim konfirmasi ke customer
    $wa_customer = '62' . ltrim($customer_phone, '0');
    $wa_customer_message = "*TERIMA KASIH - ENSSKIN BEAUTY GLOW*\n\n";
    $wa_customer_message .= "Halo " . $customer_name . ",\n\n";
    $wa_customer_message .= "Booking Anda telah kami terima dengan detail:\n\n";
    $wa_customer_message .= "ID Booking: #" . str_pad($booking_id, 5, '0', STR_PAD_LEFT) . "\n";
    $wa_customer_message .= "Treatment: " . $treatment_type . "\n";
    $wa_customer_message .= "Tanggal: " . date('d M Y', strtotime($booking_date)) . "\n";
    $wa_customer_message .= "Waktu: " . $booking_time . "\n\n";
    $wa_customer_message .= "Status: *Menunggu Konfirmasi*\n\n";
    $wa_customer_message .= "Tim kami akan segera menghubungi Anda untuk konfirmasi.\n\n";
    $wa_customer_message .= "Terima kasih telah mempercayai Ensskin Beauty Glow! ✨";
    
    $wa_customer_url = "https://wa.me/" . $wa_customer . "?text=" . urlencode($wa_customer_message);
    
    // Set session success
    $_SESSION['booking_success'] = true;
    $_SESSION['booking_id'] = $booking_id;
    $_SESSION['wa_admin_url'] = $wa_url;
    $_SESSION['wa_customer_url'] = $wa_customer_url;
    
    // Redirect ke halaman sukses
    header('Location: booking-success.php');
    exit;
    
} else {
    $_SESSION['booking_error'] = 'Booking gagal. Silakan coba lagi!';
    header('Location: index.php#booking');
    exit;
}
?>