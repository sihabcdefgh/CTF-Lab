<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: welcome.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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

        .dashboard-container {
            text-align: center;
            background-color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .dashboard-container h2 {
            color: #4CAF50;
            font-size: 24px;
        }

        .dashboard-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .dashboard-container a:hover {
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
    <div class="dashboard-container">
        <h2>Welcome</h2>
        <a href="register.php">Sign Up</a>
        <a href="login.php">Sign In</a>
    </div>

    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
