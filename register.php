<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: welcome.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = "";
    $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";

    $response = file_get_contents($verifyUrl . "?secret=" . $secretKey . "&response=" . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
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

        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $nim = $_POST['nim'];
        $email = $_POST['email'];
        $nama_kelompok_id = $_POST['nama_kelompok'];

        $checkUsernameQuery = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($checkUsernameQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "Username or Email already used!";
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || 
                !preg_match("/@gmail\.com$|@students\.amikom\.ac\.id$|@yahoo\.com$/", $email)) {
                $error_message = "Email tidak valid atau bukan Gmail, @students.amikom.ac.id, atau Yahoo!";
            } else {
                $stmt->close();
                
                $resetToken = NULL;
                $resetTokenExpiry = NULL;

                $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, nim, email, reset_token, reset_token_expiry, nama_kelompok_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssi", $nama_lengkap, $username, $password, $nim, $email, $resetToken, $resetTokenExpiry, $nama_kelompok_id);

                if ($stmt->execute()) {
                    echo "<script>
                            alert('Registration Success!');
                            window.location.href = 'login.php';
                          </script>";
                    exit();
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            }
        }

        $stmt->close();
        $conn->close();
    }
}

$servername = "";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$kelompokQuery = "SELECT id, nama_kelompok FROM kelompok";
$kelompokResult = $conn->query($kelompokQuery);
$kelompokOptions = [];

if ($kelompokResult->num_rows > 0) {
    while($row = $kelompokResult->fetch_assoc()) {
        $kelompokOptions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('qQQqQQ.png');
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

        .register-container {
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
        input[type="password"],
        input[type="email"],
        select {
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
        input[type="password"]:focus,
        input[type="email"]:focus,
        select:focus {
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
    <div class="register-container">
        <h2>Register New Account</h2>
        <form action="" method="POST">
            <input type="text" name="nama_lengkap" placeholder="Full Name" required><br>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="email" name="email" placeholder="you@email.com" required><br>
            <input type="text" name="nim" placeholder="NIM" required><br>
            
            <select name="nama_kelompok" required>
                <option value="">Select Group</option>
                <?php foreach ($kelompokOptions as $kelompok): ?>
                    <option value="<?php echo $kelompok['id']; ?>"><?php echo $kelompok['nama_kelompok']; ?></option>
                <?php endforeach; ?>
            </select><br>

            <div class="g-recaptcha" data-sitekey=""></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <input type="submit" value="Sign Up">
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <a href="dashboard.php" class="back-home-btn">Back to Dashboard</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
