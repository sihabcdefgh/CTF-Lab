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

if (isset($_GET['challenge_completed'])) {
    $update_time_query = $mysqli->prepare("UPDATE users SET waktu_selesai = NOW() WHERE username = ?");
    $update_time_query->bind_param("s", $_SESSION['username']);
    $update_time_query->execute();
}

$result = $mysqli->query("SELECT username, poin, nama_kelompok_id, waktu_selesai FROM users ORDER BY poin DESC, waktu_selesai ASC");

$username = $_SESSION['username'];

$rankings = [];
$rank = 1;
while ($row = $result->fetch_assoc()) {
    $kelompok_query = $mysqli->prepare("SELECT nama_kelompok FROM kelompok WHERE id = ?");
    $kelompok_query->bind_param("i", $row['nama_kelompok_id']);
    $kelompok_query->execute();
    $kelompok_query->bind_result($nama_kelompok);
    $kelompok_query->fetch();
    $kelompok_query->close();

    $rankings[] = [
        'username' => $row['username'],
        'poin' => $row['poin'],
        'rank' => $rank,
        'nama_kelompok' => $nama_kelompok,
        'waktu_selesai' => $row['waktu_selesai']
    ];
    $rank++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard</title>
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

        .scoreboard-container {
            width: 90%;
            max-width: 1100px;
            background: #2e3338;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        h2 {
            font-size: 2.5rem;
            color: #00bcd4;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .table-container {
            max-height: 675px; 
            overflow-y: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            transition: all 0.3s ease;
        }

        table th, table td {
            padding: 12px 15px;
            font-size: 1.2rem; 
            border-radius: 10px;
            text-align: center;
        }

        table th {
            background: #37474f;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table td {
            background: #455a64;
            color: #fff;
            font-weight: normal;
        }

        table tr:nth-child(even) {
            background: #4e5d61;
        }

        .rank {
            font-weight: bold;
            color: #ff9800;
        }

        .you {
            font-weight: bold;
            color: #00ff89;
            text-shadow: 0 0 10px #00ff89;
        }

        .medal {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .gold img, .silver img, .bronze img {
            width: 100%;
            height: 100%;
        }

        table tr:hover {
            background: #607d8b;
        }

        table td:hover {
            background: #b0bec5;
        }

        .rank-column {
            width: 10%;
        }

        .username-column {
            width: 40%;
        }

        .points-column {
            width: 10%;
        }

        .medal-column {
            width:15%;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            text-align: center;
            width: 100%;
            color: #bbb;
            font-size: 14px;
        }

        .back-home {
            margin-top: 20px;
        }

        .back-home button {
            background-color: #00bcd4;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .back-home button:hover {
            background-color: #0097a7;
        }
        

        @media screen and (max-width: 768px) {
            table {
                font-size: 1rem;
            }
            .scoreboard-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="scoreboard-container">
        <h2>Leaderboard</h2>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="rank-column">Rank</th>
                        <th class="username-column">Username</th>
                        <th class="points-column">Points</th>
                        <!-- <th class="medal-column">Medal</th> -->
                        <th class="time-column">Last Submited</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rankings as $ranking): ?>
    <tr>
        <td class="rank">
            <?php
                if ($ranking['rank'] == 1) {
                    echo $ranking['rank'] . "st";
                } elseif ($ranking['rank'] == 2) {
                    echo $ranking['rank'] . "nd";
                } elseif ($ranking['rank'] == 3) {
                    echo $ranking['rank'] . "rd";
                } else {
                    echo $ranking['rank'] . "th";
                }
            ?>
        </td>
        <td class="<?php echo $ranking['username'] == $username ? 'you' : ''; ?>">
            <?php if ($ranking['rank'] <= 3): ?>
                <img src="fire.gif" alt="User Icon" style="width: 50px; height: 50px; margin-right: 8px;">
            <?php endif; ?>
            <?php 
                echo htmlspecialchars($ranking['nama_kelompok']) . " " . htmlspecialchars($ranking['username']); 
                if ($ranking['username'] == $username) {
                    echo " (You)";
                }
            ?>
        </td>
        <td><?php echo $ranking['poin']; ?></td>
        <td><?php echo date("Y-m-d H:i:s", strtotime($ranking['waktu_selesai'])); ?></td>
    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="back-home">
            <a href="welcome.php">
                <button>Back</button>
            </a>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div>
</body>
</html>
