<?php
require_once 'includes/db.php';
require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';
require_once 'includes/phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(16));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        $user_id = $user['id'];

        $conn->query("DELETE FROM password_resets WHERE user_id = $user_id");

        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $token, $expires);
        $stmt->execute();

        // 📧 Send email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use your mail server
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Your email
            $mail->Password = 'your_app_password';    // Use app password if 2FA is on
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'Contact Manager');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Link';
            $mail->Body = "Click the link to reset your password: <a href='http://localhost/contact_manager/edit_password.php?token=$token'>Reset Password</a>";

            $mail->send();
            echo "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "No user found with that email.";
    }
}
?>

<h2>Forgot Password</h2>
<form method="POST">
    Enter your email: <input type="email" name="email" required><br><br>
    <button type="submit">Request Reset</button>
</form>
