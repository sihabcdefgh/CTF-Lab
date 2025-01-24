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

$challenges = [];
$result = $mysqli->query("SELECT name, level FROM challenges");
while ($row = $result->fetch_assoc()) {
    $challenges[] = $row;
}

$username = $_SESSION['username'];
$stmt = $mysqli->prepare("SELECT completed_challenges, poin, level FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($completed_challenges, $poin, $level);
$stmt->fetch();
$stmt->close();

$completed_challenges_array = $completed_challenges ? explode(',', $completed_challenges) : [];

$available_challenges = [];
foreach ($challenges as $challenge) {
    if ($challenge['level'] <= $level && !in_array($challenge['name'], $completed_challenges_array)) {
        $available_challenges[] = $challenge['name'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $challenge_name = $_POST['challenge_name'];
    $flag = $_POST['flag'];

    $stmt = $mysqli->prepare("SELECT flag, points FROM challenges WHERE name = ?");
    $stmt->bind_param("s", $challenge_name);
    $stmt->execute();
    $stmt->bind_result($correct_flag, $points);
    $stmt->fetch();
    $stmt->close();

    if (in_array($challenge_name, $completed_challenges_array)) {
         $message = "Allready Submited! <img src='emoji-icons/warning.gif' alt='Icon' style='width: 70px; height: 70px; vertical-align: middle;'>";
    } else {
        if ($flag === $correct_flag) {
            $new_completed_challenges = $completed_challenges ? $completed_challenges . "," . $challenge_name : $challenge_name;
            $new_poin = $poin + $points;

            $new_level = count($completed_challenges_array) + 2;

            $stmt = $mysqli->prepare("UPDATE users SET poin = ?, level = ?, completed_challenges = ? WHERE username = ?");
            $stmt->bind_param("isss", $new_poin, $new_level, $new_completed_challenges, $username);
            $stmt->execute();
            $stmt->close();

            $message = "Correct, you get $points points. <img src='emoji-icons/correct.gif' alt='Icon' style='width: 70px; height: 60px; vertical-align: middle;'>";
        } else {
            $message = "Wrong Flag! <img src='emoji-icons/incorrect.gif' alt='Icon' style='width: 70px; height: 70px; vertical-align: middle;'>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Flag</title>
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

.flag-container {
    background-color: #333;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 500px;
    text-align: center;
    color: #fff;
}

select, input[type="text"], button {
    background-color: #444;
    color: #fff;
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #666;
    box-sizing: border-box;
}

button {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

.message {
    margin-top: 20px;
    font-size: 1rem;
    color: green;
}

.error {
    color: red;
}

.back-button {
    background-color: #f1f1f1;
    color: #333;
    font-size: 16px;
    text-decoration: none;
    display: inline-block;
    padding: 12px 20px;
    margin-top: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.back-button:hover {
    background-color: #ddd;
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
    <div class="flag-container">
        <h2>Input Flag</h2>
        <form method="post">
            <label for="challenge_name">Challenge:</label>
            <select name="challenge_name" id="challenge_name" required>
                <?php foreach ($available_challenges as $challenge): ?>
                    <option value="<?php echo htmlspecialchars($challenge); ?>">
                        <?php echo htmlspecialchars($challenge); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="flag">Input flag here:</label>
            <input type="text" name="flag" id="flag" required placeholder="root{FLAG}">
            <button type="submit">Submit</button>
        </form>
        <?php if (isset($message)): ?>
            <p class="message <?php echo isset($correct_flag) && $flag === $correct_flag ? '' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        <a href="welcome.php" class="back-button">Back</a>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
