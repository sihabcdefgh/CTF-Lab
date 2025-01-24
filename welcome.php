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

$stmt = $mysqli->prepare("SELECT poin, level, nama_kelompok_id FROM users WHERE username = ?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$stmt->bind_result($poin, $level, $nama_kelompok_id);
$stmt->fetch();
$stmt->close();

if ($poin === 0) {
    $poin = 10; 
    $update_stmt = $mysqli->prepare("UPDATE users SET poin = ? WHERE username = ?");
    $update_stmt->bind_param("is", $poin, $_SESSION['username']);
    $update_stmt->execute();
    $update_stmt->close();
}

$kelompok_query = $mysqli->prepare("SELECT nama_kelompok FROM kelompok WHERE id = ?");
$kelompok_query->bind_param("i", $nama_kelompok_id);
$kelompok_query->execute();
$kelompok_query->bind_result($nama_kelompok);
$kelompok_query->fetch();
$kelompok_query->close();

$query = "SELECT name, points, level FROM challenges WHERE level <= ? ORDER BY points ASC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $level);
$stmt->execute();
$result = $stmt->get_result();

$challenges = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $challenges[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('qQQqQQ.png');
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

        .welcome-container {
            text-align: center;
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            width: 90%; 
            max-width: 1200px;
            min-width: 500px; 
            box-sizing: border-box;
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        h3 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #b0b0b0;
        }

        .folder-list {
            display: flex;
            justify-content: center; 
            gap: 20px; 
            flex-wrap: wrap; 
            margin-top: 40px;
        }

        .folder-item {
            text-decoration: none;
            color: #4CAF50;
            font-size: 1.25rem;
            font-weight: bold;
            padding: 20px;
            background-color: #2e2e2e;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }

        .folder-item:hover {
            background-color: #4CAF50;
            color: white;
            transform: translateY(-5px); 
        }

        .locked {
            background-color: #666;
            color: #aaa;
            border: 2px solid #ccc;
        }

        .locked:hover {
            background-color: #555;
            color: #ddd;
            cursor: not-allowed;
        }

        .logout-btn {
            margin-top: 40px;
            padding: 12px 25px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
            transform: translateY(-5px); 
        }

        .flag-btn {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .flag-btn:hover {
            background-color: #0056b3;
            transform: translateY(-5px); 
        }

        .scoreboard-btn {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .scoreboard-btn:hover {
            background-color: #218838;
            transform: translateY(-5px); 
        }

        .poin {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #e0e0e0;
        }

        .level {
            margin-top: 10px;
            font-size: 1.2rem;
            color: #e0e0e0;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: #888;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .folder-item {
                font-size: 1.1rem;
                padding: 15px;
            }
            .welcome-container {
                padding: 30px;
            }
            h2 {
                font-size: 2rem;
            }
            h3 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h2>Welcome, <?php echo htmlspecialchars($nama_kelompok); ?>  <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <p class="poin">Your Point: <?php echo $poin; ?></p>
        
        <p class="level">
            Your Level: 
            <?php 
                if ($level >= 11) {
                    echo "Complete 10/10";
                } else {
                    echo $level;
                }
            ?>
        </p> 

        <h3>Select Challenge:</h3>
        <div class="folder-list">
            <?php 
            foreach ($challenges as $challenge): 
                if ($poin < $challenge['points']) {
                    echo '<span class="folder-item locked">Locked</span>'; 
                } else {
                    echo '<a href="' . $challenge['name'] . '" class="folder-item">' . $challenge['name'] . '</a>';
                }
            endforeach; 
            ?>
        </div>

        <a href="scoreboard.php" class="scoreboard-btn">Scoreboard</a>
        <a href="logout.php" class="logout-btn">Logout</a>
        <a href="input_flag.php" class="flag-btn">Input Flag</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
