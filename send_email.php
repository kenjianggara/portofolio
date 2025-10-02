<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . '/vendor/autoload.php';                // ← autoload Composer
$config = require '/etc/portofolio/config.mail.php';     // ← config di luar webroot (aman)

// ====== KONFIG DB ======
$servername = "localhost";
$username   = "admin";
$password   = "";
$dbname     = "personalweb";

// ====== KONEKSI DB ======
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek DB ada
$db_check_query   = "SHOW DATABASES LIKE '$dbname'";
$db_check_result  = $conn->query($db_check_query);
if ($db_check_result->num_rows == 0) {
    die("Database tidak ditemukan");
}
$conn->select_db($dbname);

// Cek table ada
$table_check_query  = "SHOW TABLES LIKE 'contact'";
$table_check_result = $conn->query($table_check_query);
if ($table_check_result->num_rows == 0) {
    die("Tabel 'contact' tidak ditemukan");
}

// ====== Ambil & validasi form ======
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: contact.php?status=invalid");
    exit;
}
if ($name === '' || $message === '') {
    header("Location: contact.php?status=invalid");
    exit;
}

// ====== Simpan ke DB ======
$stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
if (!$stmt) {
    error_log("DB_PREPARE_ERROR: " . $conn->error);
    header("Location: ./contact.php?status=dberror");
    exit;
}
$stmt->bind_param("sss", $name, $email, $message);
if (!$stmt->execute()) {
    error_log("DB_EXEC_ERROR: " . $stmt->error);
    header("Location: ./contact.php?status=dberror");
    exit;
}
$stmt->close();
$conn->close();

// ====== Kirim Email via Brevo (SMTP) ======
$mail = new PHPMailer(true);
try {
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // ← aktifkan sementara untuk debug

    $mail->isSMTP();
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

    // From = domain kamu (align DMARC), To = email penerima
    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email'], $config['to_name']);

    // Supaya balas langsung ke pengirim form
    $mail->addReplyTo($email, $name);

    // Konten
    $mail->isHTML(true);
    $mail->Subject = "[Contact Form] Pesan baru dari $name";
    $html  = "<h3>Pesan baru dari form kontak</h3>";
    $html .= "<p><b>Nama:</b> " . htmlspecialchars($name) . "</p>";
    $html .= "<p><b>Email:</b> " . htmlspecialchars($email) . "</p>";
    $html .= "<p><b>Waktu:</b> " . date('Y-m-d H:i:s') . "</p>";
    $html .= "<p><b>Pesan:</b><br>" . nl2br(htmlspecialchars($message)) . "</p>";
    $mail->Body    = $html;

    $text  = "Pesan baru dari form kontak\n";
    $text .= "Nama: $name\nEmail: $email\nWaktu: " . date('Y-m-d H:i:s') . "\n\n";
    $text .= "Pesan:\n$message\n";
    $mail->AltBody = $text;

    // Header tambahan (opsional)
    $mail->addCustomHeader('X-Form', 'portfolio-contact');

    $mail->send();

    // Sukses semuanya
    header("Location: ./contact.php?status=success");
    exit;

} catch (\Throwable $e) {
    error_log('MAIL_ERROR: ' . $e->getMessage());
    // Data sudah tersimpan di DB, tapi email gagal → kirim status berbeda
    header("Location: ./contact.php?status=mailfail");
    exit;
}
            