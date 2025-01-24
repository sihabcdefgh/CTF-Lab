<?php
session_start();

$db_host = '';
$db_user = '';
$db_pass = '';
$db_name = '';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";

if (isset($_GET['email']) && isset($_GET['token'])) {
    $_SESSION['email'] = $_GET['email'];
    $_SESSION['token'] = $_GET['token'];
    header("Location: reset_password.php");
    exit();
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$token = isset($_SESSION['token']) ? $_SESSION['token'] : '';

if (!empty($email) && !empty($token)) {
    $stmt = $conn->prepare("SELECT id, email, reset_token_expiry FROM users WHERE email = ? AND reset_token = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_email, $reset_token_expiry);
        $stmt->fetch();

        if (strtotime($reset_token_expiry) < time()) {
            $error_message = "Token telah kedaluwarsa. Silakan coba lagi.";
        } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_password = trim($_POST['new_password']);
                $confirm_password = trim($_POST['confirm_password']);

                if (empty($new_password) || empty($confirm_password)) {
                    $error_message = "Password baru dan konfirmasi password tidak boleh kosong.";
                } elseif (strlen($new_password) < 8) {
                    $error_message = "Password must be 8 character minimum!";
                } elseif ($new_password !== $confirm_password) {
                    $error_message = "Password do not match";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    $stmt_update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
                    $stmt_update->bind_param("si", $hashed_password, $user_id);
                    if ($stmt_update->execute()) {
                        $success_message = "Password Updated!";
                    } else {
                        $error_message = "There is an ERROR!";
                    }
                    $stmt_update->close();
                }
            }
        }
    } else {
        $error_message = "Token atau email tidak valid.";
    }
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .container {
            background-color: #2a2a2a; 
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.4); 
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #f4f7fc; 
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-size: 14px;
            color: #f4f7fc; 
        }

        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message, .success-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

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

        .back-login-btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        
        <?php if (!empty($error_message)) echo "<div class='error-message'>" . htmlspecialchars($error_message) . "</div>"; ?>
        <?php if (!empty($success_message)) echo "<div class='success-message'>" . htmlspecialchars($success_message) . "</div>"; ?>

        
        <form method="POST" action="">
            <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <div class="g-recaptcha" data-sitekey=""></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            
            <input type="submit" value="Reset Password">
            <a href="login.php" class="back-login-btn">Back to Login</a>
        </form>
    </div>
</body>
</html>
