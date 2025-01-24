<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$host = '';
$dbname = '';
$db_user = '';
$db_password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $db_user,
        $db_password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $username = $_SESSION['username'];
    $stmt = $pdo->prepare("SELECT level FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $level = $stmt->fetchColumn();

    if (!$level) {
        header("Location: ../no_access.php");
        exit();
    }

    $challenge_name = "cipher";
    $required_level = 3;

    if ($level < $required_level) {
        header("Location: ../no_access.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT flag FROM challenges WHERE name = :name");
    $stmt->execute([':name' => $challenge_name]);
    $flag = $stmt->fetchColumn();

    if (!$flag) {
        die("Flag tidak ditemukan di database.");
    }

    $original_message = $flag;
    $encrypted_message = str_rot13($original_message);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cipher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .message {
            margin-top: 20px;
            font-size: 1.25rem;
            color: #e0e0e0;
        }
        .encrypted-message {
            margin-top: 30px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #4CAF50;
        }
        .buttons {
            margin-top: 40px;
        }
        .buttons a {
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            font-size: 1rem;
        }
        .buttons a:hover {
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
    <div class="container">
        <p class="encrypted-message"><strong><?php echo htmlspecialchars($encrypted_message); ?></strong></p><br><br>

        <div class="buttons">
            <a href="../welcome.php">Back</a>
            <a href="../input_flag.php">Submit Flag</a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
