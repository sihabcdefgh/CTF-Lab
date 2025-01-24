<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Access</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('qQQqQQ.png'); 
            background-size: cover; 
            background-position: center; 
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #e0e0e0;
            text-align: center;
        }

        h1 {
            color: #f8d7da;
            font-size: 36px;
            margin-bottom: 10px;
        }

        p {
            color: #f8d7da;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .home-btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 150px;
            text-align: center;
            margin-top: 20px;
        }

        .home-btn:hover {
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

        .image-container {
            margin: 20px 0;
        }

        .image-container img {
            max-width: 300px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Access Denied</h1>
    <p>You don't have the required level to access this page!</p>

    <div class="image-container">
        <img src="emoji-icons/warning.gif" alt="Access Denied Image">
    </div>

    <a href="welcome.php">
        <button class="home-btn">Back to Home</button>
    </a>

    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
