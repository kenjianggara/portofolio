<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// ===== Debug & safety =====
ini_set('display_errors', '0');     // jangan tampilkan error ke user
ini_set('log_errors', '1');         // log ke Apache error.log
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ob_start();                         // hindari "headers already sent"

// ===== Autoload & config =====
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    error_log('AUTOLOAD_NOT_FOUND: ' . $autoload);
    header('Location: ./contact.php?status=server');
    exit;
}
require $autoload;

$configPath = '/etc/config.mail.php';
if (!is_readable($configPath)) {
    error_log('CONFIG_NOT_READABLE: ' . $configPath);
    header('Location: ./contact.php?status=cfg');
    exit;
}
$config = require $configPath;

// ===== Validasi request =====
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

function f(string $k): string { return trim($_POST[$k] ?? ''); }
$name    = f('name');
$email   = f('email');
$message = f('message');

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ./contact.php?status=invalid');
    exit;
}

// ===== DB: connect & insert =====
$servername = 'localhost';
$username   = 'admin';   // sesuaikan
$password   = '';        // sesuaikan
$dbname     = 'personalweb';

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');

    // langsung coba insert; kalau tabel belum ada, akan ke-catch dengan pesan jelas
    $stmt = $conn->prepare('INSERT INTO contact (name, email, message) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $message);
    $stmt->execute();
    $stmt->close();
    $conn->close();
} catch (\mysqli_sql_exception $e) {
    error_log('DB_ERROR: ' . $e->getMessage());
    header('Location: ./contact.php?status=dberror');
    exit;
}

// ===== Kirim email via Brevo SMTP =====
$mail = new PHPMailer(true);

try {
    // Aktifkan sementara jika perlu lihat detail di error.log:
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    

    $mail->isSMTP();
    $mail->Mailer = 'smtp'; // cegah fallback ke mail()/sendmail lokal
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];

    if (($config['encryption'] ?? 'tls') === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int)($config['port'] ?? 465);
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)($config['port'] ?? 587);
    }

    // From (domain kamu) & To (email tujuan)
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->Sender = $config['from_email']; // set Return-Path/envelope sender
    error_log('MAIL_FROM=' . $mail->From . ' SENDER=' . $mail->Sender);
    $mail->addAddress($config['to_email'], $config['to_name']);

    // Reply ke pengirim form
    $mail->addReplyTo($email, $name);

    // Konten
    $mail->isHTML(true);
    $mail->Subject = "[Contact Form] Pesan baru dari $name";
    $mail->Body    =
        "<h3>Pesan baru dari form kontak</h3>"
      . "<p><b>Nama:</b> " . htmlspecialchars($name) . "</p>"
      . "<p><b>Email:</b> " . htmlspecialchars($email) . "</p>"
      . "<p><b>Waktu:</b> " . date('Y-m-d H:i:s') . "</p>"
      . "<p><b>Pesan:</b><br>" . nl2br(htmlspecialchars($message)) . "</p>";

    $mail->AltBody =
        "Pesan baru dari form kontak\n"
      . "Nama: $name\nEmail: $email\nWaktu: " . date('Y-m-d H:i:s') . "\n\n"
      . "Pesan:\n$message\n";

    // Header tambahan
    $mail->addCustomHeader('X-Form', 'portfolio-contact');

    $mail->send();

    header('Location: ./contact.php?status=success');
    exit;

} catch (\Throwable $e) {
    error_log('MAIL_ERROR: ' . $e->getMessage());
    header('Location: ./contact.php?status=mailfail');
    exit;
}

// (jangan tutup dengan "?>" agar tidak ada whitespace yang mengganggu header)
