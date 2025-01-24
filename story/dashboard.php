<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
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

$challenge_name = "story";  
$required_level = 8; 

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
    <title>Story</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../qQQqQQ.png');
            color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 100vh;
            text-align: center;
            flex-direction: column; 
        }

        header {
            background-color: #1e88e5;
            padding: 10px 0;
            width: 100%;
            text-align: center;
            position: fixed; 
            top: 0;
            left: 0;
            z-index: 10;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
            color: #fff;
        }

        .container {
            max-width: 800px;
            padding: 330px;
            background-color: #1f1f1f;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-top: 80px;
            text-align: center;
        }
	.user {
            display: none;
        }

        .challenge-info {
            margin-bottom: 20px;
        }

        .challenge-info h2 {
            color: #42a5f5;
            margin-bottom: 10px;
        }

        .actions {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            display: block;
            background-color: #42a5f5;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background 0.3s ease;
            width: 200px;
            margin: 0 auto; 
        }

        .btn:hover {
            background-color: #1e88e5;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="challenge-info">
            <h2>There are many secrets from the past</h2>
        </div>

        <div class="actions">
            <a href="../welcome.php" class="btn">Back</a>
            <a href="../input_flag.php" class="btn">Submit Flag</a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
