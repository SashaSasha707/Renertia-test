<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    ini_set('display_errors', 1);  // Для тестування, щоб показувати помилки
    error_reporting(E_ALL);

    // Отримання даних з форми
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone']);
    $state = htmlspecialchars($_POST['state']);
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : 'No message provided';

    $mail = new PHPMailer(true);

    try {
        // Налаштування SMTP для адміністратора
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'];

        // Відправник
        $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
        $mail->addAddress($_ENV['SMTP_FROM_EMAIL']); // Пошта адміністратора

        // Тема та тіло листа
        $mail->Subject = "New Contact Form Submission from $firstName $lastName";
        $body = "Name: $firstName $lastName\nEmail: $email\nPhone: $phone\nState: $state\n\nMessage:\n$message";
        $mail->Body = $body;

        // Прикріплення файлу
        if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
        }

        // Надсилання листа
        $mail->send();

        // Відповідь користувачу
        $userMail = new PHPMailer(true);
        $userMail->isSMTP();
        $userMail->Host = $_ENV['SMTP_HOST'];
        $userMail->SMTPAuth = true;
        $userMail->Username = $_ENV['SMTP_USERNAME'];
        $userMail->Password = $_ENV['SMTP_PASSWORD'];
        $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $userMail->Port = $_ENV['SMTP_PORT'];

        $userMail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
        $userMail->addAddress($email);  // Відправка на email користувача
        $userMail->Subject = "Thank you for contacting Renertia";
        $userMail->Body = "Dear $firstName,\n\nThank you for reaching out to Renertia! We’ve received your inquiry and will get back to you as soon as possible.\n\nBest regards,\nThe Renertia Team";
        $userMail->send();

        echo "success";
    } catch (Exception $e) {
        // Виведення помилки, якщо вона виникає
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>