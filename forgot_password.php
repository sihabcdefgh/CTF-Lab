<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = "";
$success_message = "";

$db_host = '';
$db_user = '';
$db_pass = '';
$db_name = '';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

function generateResetToken($length = 32) {
    return bin2hex(random_bytes($length));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email not valid.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $reset_token = generateResetToken();
            $reset_token_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

            $stmt_update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
            $stmt_update->bind_param("ssi", $reset_token, $reset_token_expiry, $user_id);
            if ($stmt_update->execute()) {
                $domain = $_SERVER['HTTP_HOST'];
                $resetLink = "http://$domain/reset_password.php?email=" . urlencode($email) . "&token=" . urlencode($reset_token);

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('no-reply@????????????????', 'No Reply');
                    $mail->addAddress($email, 'User');

                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Password';
                    $mail->Body    = "Klik link berikut untuk mereset password Anda: <a href='$resetLink'>Reset Password</a>";

                    $mail->send();
                    $success_message = 'Check your email to reset password!';
                } catch (Exception $e) {
                    $error_message = "Failed to send email!. Error: {$mail->ErrorInfo}";
                }
            } else {
                $error_message = "There is an ERROR!";
            }
            $stmt_update->close();
        } else {
            $error_message = "Email not found!";
        }
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('qQQqQQ.png'); /
            background-size: cover; 
            background-position: center; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #e0e0e0;
        }

        .forgot-password-container {
            background-color: #333;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .forgot-password-container h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus {
            border-color: #4CAF50;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 20px;
            font-size: 14px;
        }

        .success-message {
            color: green;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: #888;
            font-size: 14px;
        }

        .back-home-btn,
        .back-login-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .back-home-btn:hover,
        .back-login-btn:hover {
            background-color: #45a049;
        }

        .back-home-btn {
            margin-left: 10px;
        }
    </style>

</head>
<body>

    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form method="POST" onsubmit="showSuccessMessage(event)">
            <input type="email" name="email" placeholder="Enter your email" required><br>
            <div class="g-recaptcha" data-sitekey=""></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <input type="submit" value="Submit">
        </form>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <a href="login.php" class="back-login-btn">Back to Login</a>
        <a href="index.php" class="back-home-btn">Back to Dashboard</a>
    </div>

    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>

</body>
</html>
