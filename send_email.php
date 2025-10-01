<?php
$servername = "localhost";
$username = "admin";
$password = "";
$dbname = "personalweb";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the database exists
$db_check_query = "SHOW DATABASES LIKE '$dbname'";
$db_check_result = $conn->query($db_check_query);

if ($db_check_result->num_rows == 0) {
    die("Database tidak ditemukan"); // Custom error message for database not found
}

// Now select the database
$conn->select_db($dbname);

// Check if the table exists
$table_check_query = "SHOW TABLES LIKE 'contact'";
$table_check_result = $conn->query($table_check_query);

if ($table_check_result->num_rows == 0) {
    die("Tabel 'contact' tidak ditemukan"); // Custom error message for table not found
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email tidak valid");
}

// Store data to database
$stmt = $conn->prepare("INSERT INTO contact (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);
$stmt->execute();

// Uncomment and configure email sending if needed
// Send email
// $to = 'recipient@example.com'; // Replace with your email address
// $subject = 'New message from ' . $name;
// $body = "Name: $name\nEmail: $email\nMessage:\n$message";
// $headers = "From: $email\r\nReply-To: $email\r\n";
// mail($to, $subject, $body, $headers);

$conn->close();

// Redirect back to the contact page with success status
header("Location: ./contact.php?status=success");
exit;
?>
