<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email");
}

// store data to database
$stmt = $conn->prepare("INSERT INTO data (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);
$stmt->execute();



// // Send email
// $to = 'recipient@example.com'; // Replace with your email address
// $subject = 'New message from ' . $name;
// $body = "Name: $name\nEmail: $email\nMessage:\n$message";
// $headers = "From: $email\r\nReply-To: $email\r\n";
// mail($to, $subject, $body, $headers);


$conn->close();
// Redirect balik ke contact page dengan status sukses
header("Location: ../contact.php?status=success");
exit;
?>