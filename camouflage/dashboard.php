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

$challenge_name = "camouflage";
$required_level = 9;

if ($level < $required_level) {
    header("Location: ../no_access.php");
    exit();
}

$uploads_dir = 'uploads/';
$uploaded_files = array_diff(scandir($uploads_dir), array('..', '.'));

$images = [];
foreach ($uploaded_files as $file) {
    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
        $images[] = $file;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camouflage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../qQQqQQ.png');
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #666;
        }
        form {
            margin-top: 20px;
        }
        input[type="file"] {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .action-buttons {
            margin-top: 30px;
        }
        .action-buttons a {
            padding: 10px 20px;
            margin: 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .action-buttons a:hover {
            background-color: #0056b3;
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
        <h1>Upload image!</h1>
        
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file">Choose image:</label>
            <input type="file" name="file" id="file" accept=".jpg, .jpeg, .png, .gif" required>
            <br><br>
            <button type="submit">Upload</button>
        </form>

        <div class="action-buttons">
            <a href="../welcome.php">Back</a>
            <a href="../input_flag.php">Submit Flag</a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
