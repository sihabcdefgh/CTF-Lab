<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; 
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
            position: relative; 
        }
        img {
            max-width: 90%;
            max-height: 90%;
            border: 5px solid #3498db;
            border-radius: 10px;
        }
        .back-btn {
            position: absolute; 
            bottom: 20px; 
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <img src="assets/3.jpg" alt="Gambar di Tengah">
    <button class="back-btn" onclick="window.history.back();">Back</button>
</body>
</html>
