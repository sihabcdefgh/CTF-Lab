<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptcha_secret = '';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        $error_message = "Verifikasi reCAPTCHA gagal. Silakan coba lagi.";
    } else {
        $servername = "";
        $username = "";
        $password = "";
        $dbname = "";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header("Location: welcome.php");
                    exit();
                } else {
                    $error_message = "Username atau Password salah!";
                }
            } else {
                $error_message = "Username atau Password salah!";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
            background-color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
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

        .back-home-btn {
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

        .back-home-btn:hover {
            background-color: #45a049;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <div class="g-recaptcha" data-sitekey=""></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <input type="submit" value="Sign in">
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div><a href="forgot_password.php" class="back-home-btn">Lupa Password?</a></div>

        <a href="dashboard.php" class="back-home-btn">Back to Dashboard</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>