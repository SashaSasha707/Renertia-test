<?php
// Включаємо детальний лог помилок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Дозволяємо CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Перевіряємо, чи запит є POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Error: Only POST requests are allowed.";
    exit;
}

// Отримуємо дані форми
$firstName = htmlspecialchars($_POST['firstName'] ?? '');
$lastName = htmlspecialchars($_POST['lastName'] ?? '');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars($_POST['phone'] ?? '');
$state = htmlspecialchars($_POST['state'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Перевіряємо обов'язкові поля
if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
    echo "Error: Please fill all required fields.";
    exit;
}

// Перевірка коректності email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Error: Invalid email format.";
    exit;
}

// Адреса отримувача
$to = "aleksa700@outlook.com";
$subject = "New Contact Form Submission";

$body = "First Name: $firstName\n";
$body .= "Last Name: $lastName\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "State: $state\n";
$body .= "Message:\n$message\n";

// Відправка email
$headers = "From: $email";

if (!mail($to, $subject, $body, $headers)) {
    error_log("Mail error: Could not send message.");
    echo "error";
} else {
    echo "success";
}
?>
