<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $email = $_POST["email"];
    
    // Обробка файлу
    if (isset($_FILES["file"])) {
        $file = $_FILES["file"];
        $uploadDir = "uploads/";
        $filePath = $uploadDir . basename($file["name"]);

        if (move_uploaded_file($file["tmp_name"], $filePath)) {
            // Відправка підтвердження на email
            $to = $email;
            $subject = "Form Submission Received";
            $message = "Hello $firstName,\n\nWe have received your form submission. We will contact you soon.\n\nBest regards,\nRenertia Team";
            $headers = "From: no-reply@renertiaco.com";

            if (mail($to, $subject, $message, $headers)) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Email not sent"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "File upload failed"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "No file uploaded"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
