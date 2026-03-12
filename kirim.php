<?php
// ── Izinkan request dari origin yang sama ──
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ── Load PHPMailer ──
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// ── Ambil data dari form ──
$nama  = htmlspecialchars(trim($_POST['nama'] ?? ''));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$pesan = htmlspecialchars(trim($_POST['pesan'] ?? ''));

// ── Validasi input ──
if (empty($nama) || empty($email) || empty($pesan)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua field wajib diisi.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
    exit;
}

// ──────────────────────────────────────────
//   KONFIGURASI — ganti bagian ini
// ──────────────────────────────────────────
$gmail_user     = 'emailkamu@gmail.com';   // Gmail kamu (pengirim)
$gmail_password = 'xxxx xxxx xxxx xxxx';   // App Password Gmail (bukan password biasa!)
$tujuan_email   = 'emailkamu@gmail.com';   // Email tujuan (bisa sama atau beda)
// ──────────────────────────────────────────

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $gmail_user;
    $mail->Password   = $gmail_password;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // Pengirim & penerima
    $mail->setFrom($gmail_user, 'Portfolio Akbar');
    $mail->addAddress($tujuan_email, 'Akbar Ariffianto');
    $mail->addReplyTo($email, $nama);

    // Isi email
    $mail->isHTML(true);
    $mail->Subject = "Pesan Baru dari Portfolio — $nama";
    $mail->Body    = "
        <div style='font-family: sans-serif; max-width: 500px; margin: auto; padding: 24px; border: 1px solid #d0e3f5; border-radius: 12px;'>
            <h2 style='color: #0d1b3e; margin-bottom: 4px;'>Pesan Baru Masuk 📬</h2>
            <p style='color: #8ca3c0; font-size: 13px; margin-top: 0;'>Dari portfolio website kamu</p>
            <hr style='border: none; border-top: 1px solid #d0e3f5; margin: 16px 0;'/>
            <p><strong>Nama:</strong> $nama</p>
            <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
            <p><strong>Pesan:</strong></p>
            <div style='background: #f4f8fd; padding: 14px 16px; border-radius: 8px; color: #3a4a6a; line-height: 1.7;'>$pesan</div>
            <hr style='border: none; border-top: 1px solid #d0e3f5; margin: 16px 0;'/>
            <p style='font-size: 12px; color: #8ca3c0;'>Dikirim otomatis dari portfolio akbarariffianto.com</p>
        </div>
    ";
    $mail->AltBody = "Nama: $nama\nEmail: $email\nPesan: $pesan";

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil terkirim!']);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan: ' . $mail->ErrorInfo]);
}
?>