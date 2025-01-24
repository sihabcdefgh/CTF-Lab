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

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $mysqli->prepare("SELECT username, email, flag FROM idor WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $flag);
    $stmt->fetch();
    $stmt->close();
} else {
    $user_id = null;
}

$isFlagVisible = ($user_id === 'alice');

$isHashVisible = ($user_id == 10);

$username = $_SESSION['username'];
$stmt = $mysqli->prepare("SELECT level FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($level);
$stmt->fetch();
$stmt->close();

$challenge_name = "query-strings";
$required_level = 7;

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
    <title>Query-Strings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;  
            color: #fff; 
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;  
            padding: 20px 0;
            text-align: center;
        }
        h1 {
            margin: 0;
            font-size: 2rem;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 30px;
            background-color: #444;  
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-info {
            margin: 20px 0;
            font-size: 1.1rem;
            text-align: center;
        }
        .flag-section {
            background-color: #2c2c2c;  
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        .explanation {
            background-color: #333;
            color: #fff;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            font-size: 1rem;
        }
        .button-container {
            display: flex;
            justify-content: center;  
            gap: 20px;  
            margin-top: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;  
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 150px;  
            text-align: center;
        }
        button:hover {
            background-color: #45a049;
        }
        .center-image {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 65vh;
            margin-bottom: 5px;
        }
        .center-image img {
            max-width: 100%;
            height: 90%;
            border-radius: 10px;
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
    <header>
        <h1>User Profile Demo</h1>
    </header>

    <div class="container">
        <?php if ($user_id === null): ?>
            <div class="button-container">
                <a href="?id=1">
                    <button>Try this one</button>
                </a>
            </div>
            <div class="center-image">
                <img src="assets/picture.jpg" alt="Image">
            </div>
        <?php else: ?>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>

            <?php if ($isFlagVisible): ?>
                <div class="flag-section">
                    <p><strong>root{IDOR_1s_3a5y_t0_3xpl01t}</strong></p>
                    <div class="explanation">
                        <h2>What is IDOR?</h2>
                        <p>Insecure direct object references (IDOR) are a type of access control vulnerability...</p>
                        <div class="center-image">
                            <img src="assetssss/idor.jpg" alt="IDOR Example">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isHashVisible): ?>
                <div class="flag-section">
                    <h1>Crack this hash and replace the value with this</h1>
                    <p><strong>$6$sayang$QGQH0IEJ9k/h5ftZjNjN8FbIgOMJIKCRctg3H9osZhTKanhqBqmhntMETqmR7.WOkkyQooFPPmvK2RX1dUFz40</strong></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="button-container">
            <a href="../welcome.php">
                <button>Back</button>
            </a>
            <a href="../input_flag.php">
                <button>Input Flag</button>
            </a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>

