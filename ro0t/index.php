<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../dashboard.php");
    exit;
}

$mysqli = new mysqli('', '', '', '');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$username = $_SESSION['username'];
$stmt = $mysqli->prepare("SELECT level FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($level);
$stmt->fetch();
$stmt->close();

$challenge_name = "ro0t";
$required_level = 10;

if ($level < $required_level) {
    header("Location: ../no_access.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../qQQqQQ.png');
            background-color: #2c2c2c;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .challenge-container {
            background-color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        p {
            font-size: 1.2rem;
            color: #555;
        }

        .cta {
            font-size: 1.1rem;
            color: #007BFF;
            font-weight: bold;
        }

        code {
            background-color: #f0f0f0;
            padding: 5px;
            border-radius: 5px;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #777;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 1rem;
            color: white;
            background-color: red;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .footer-name {
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
    <div class="challenge-container">
        <p class="cta"><code>What is the root flag?</code></p>

        <div class="footer">
            <a href="../welcome.php" class="btn">Back</a>
            
            <a href="../input_flag.php" class="btn">Submit Flag</a>
        </div>
    </div>

    <div class="footer-name">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>

</body>
</html>
