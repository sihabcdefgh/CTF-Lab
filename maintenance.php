<?php
$allowed_ips = [''];

if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    header('HTTP/1.1 503 Service Unavailable');
    header('Retry-After: 3600');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maintenance</title>
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
        .container {
            background: #2c2c2c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        h1 {
            font-size: 1.5em;
            color: #e74c3c;
        }
        p {
            font-size: 1.2em;
            color: #555;
        }
        .retry {
            margin-top: 20px;
            font-size: 1em;
            color: #888;
        }
        .contact {
            margin-top: 20px;
            font-size: 1em;
            color: #3498db;
        }
        .contact a {
            color: #3498db;
            text-decoration: none;
        }
        .contact a:hover {
            text-decoration: underline;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>WEBSITE IS UNDER MAINTENANCE!</h1>
    </div>
</body>
</html>
