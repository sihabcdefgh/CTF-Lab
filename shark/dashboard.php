<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
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

$challenge_name = "shark";
$required_level = 2;

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
    <title>Shark</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #2c2c2c;
            flex-direction: column; 
            color: #fff;
        }
        .image-container {
            margin-bottom: 30px; 
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .button-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px; 
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: #fff;
        }
        .button-1 { background-color: #3498db; }
        .button-2 { background-color: #2ecc71; }
        .button-3 { background-color: #e74c3c; }
        button:hover {
            opacity: 0.8;
        }
        .hint-button {
            background-color: #f1c40f;
            color: #2c2c2c;
        } 
        .page-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #34495e; 
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        .footer {
            position: absolute;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: #888;
            font-size: 14px;
        }
        .page-button:hover {
            background-color: #2c3e50; 
        }
        .hint-container {
            margin-top: 20px;
            font-size: 14px;
            display: none; 
            color: #f39c12;
        }
    </style>
    <script>
        function showHint() {
            var hint = document.getElementById('hint');
            hint.style.display = 'block'; 
        }
    </script>
</head>
<body>
    <div class="image-container">
        <img src="assets/sus-dog.gif" alt="Logo" /> 
    </div>

    <div class="button-container">
        <form method="POST">
            <button name="button" value="1" class="button-1">Click Me!</button>
            <button name="button" value="2" class="button-2">Click Me!</button>
            <button name="button" value="3" class="button-3">Click Me!</button>
        </form>
    </div>

    <button class="hint-button" onclick="showHint()">Show Hint</button>
    
    <div id="hint" class="hint-container">
        <p>Traffic always leaves a trace</p>
    </div>

    <form action="../input_flag.php" method="get">
        <button type="submit" class="page-button">Submit Flag</button>
    </form>
    <form action="../welcome.php" method="get">
        <button type="submit" class="page-button">Back</button>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $button = $_POST['button'];

        switch ($button) {
            case '1':
                header("Location: halaman1.php");
                exit();
            case '2':
                header("Location: halaman2.php");
                exit();
            case '3':
                header("Location: haIaman3.php");
                exit();
            default:
                echo "<p>Invalid action!</p>";
        }
    }
    ?>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
