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

$challenge_name = "find";
$required_level = 5;

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
    <title>Find</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2c2c2c;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            text-align: center;
            padding: 20px;
            background-color: #444444;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        header p {
            font-size: 1.2rem;
            margin-top: 10px;
            color: #cccccc;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            width: 100%;
        }

        .gallery-item {
            background-color: #333333;
            border: 2px solid #555555;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.8);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.9);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
        }

        .caption {
            padding: 10px;
            font-size: 1.1rem;
            color: #ffffff;
        }

        .header-buttons {
    display: flex;
    justify-content: center;
    gap: 20px; 
    margin-top: 20px;
    }

    .header-buttons button {
        padding: 10px 20px;
        font-size: 1rem;
        cursor: pointer;
        background-color: #555555;
        border: none;
        color: white;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .header-buttons button:hover {
        background-color: #444444;
    }
	.hint-button {
    background-color: #f1c40f;
    color: #2c2c2c;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}

.hint-button:hover {
    background-color: #e67e22;
}

.hint-container {
    margin-top: 20px;
    font-size: 14px;
    display: none; 
    color: #f39c12;
}



        footer {
            text-align: center;
            padding: 10px;
            background-color: #444444;
            margin-top: 20px;
            width: 100%;
            color: #cccccc;
        }

        @media (max-width: 768px ) {
            .gallery {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .gallery {
                grid-template-columns: 1fr;
            }
        }
    </style>
     <script>
        function setCookie(name, value, days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            let expires = "expires=" + date.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }
        function getCookie(name) {
            let decodedCookie = decodeURIComponent(document.cookie);
            let cookies = decodedCookie.split(';');
            name = name + "=";
            for (let i = 0; i < cookies.length; i++) {
                let cookie = cookies[i].trim();
                if (cookie.indexOf(name) === 0) {
                    return cookie.substring(name.length, cookie.length);
                }
            }
            return "";
        }
        function saveMe() {
            const me = "dGgxc19pc190aDNfRmxhZ30=";
            setCookie("Me", me, 7); 
            alert(".");
        }
        function showMe() {
            let me = getCookie("me");
            if (me) {
                alert("Me: " + me);
            } else {
                alert("...");
            }
        }
    </script>
<script>
    function showHint() {
        var hint = document.getElementById('hint');
        hint.style.display = 'block';
    }
</script>

</head>
<body>
<header>
    <h1>Scientist Gallery</h1>
    <p>Honoring scientists who have changed the world</p>

    <div class="header-buttons">
        <a href="../welcome.php">
            <button class="home-btn">Back</button>
        </a>
        <a href="../input_flag.php">
            <button class="submit-btn">Submit Flag</button>
        </a>
    </div>
</header>


    <div class="gallery">
        <div class="gallery-item">
            <img src="assets/einstein.jpg" alt="Albert Einstein">
            <div class="caption">Albert Einstein</div>
        </div>
        <div class="gallery-item">
            <img src="assets/curie.jpg" alt="Marie Curie">
            <div class="caption">Marie Curie</div>
        </div>
        <div class="gallery-item">
            <img src="assets/newton.jpg" alt="Isaac Newton">
            <div class="caption">Isaac Newton</div>
        </div>
        <div class="gallery-item">
            <img src="assets/tesla.jpeg" alt="Nikola Tesla">
            <div class="caption">Nikola Tesla</div>
        </div>
        <div class="gallery-item">
            <img src="assets/darwin.jpg" alt="Charles Darwin">
            <div class="caption">Charles Darwin</div>
        </div>
        <div class="gallery-item">
            <img src="assets/galileo.jpg" alt="Galileo Galilei">
            <div class="caption">Galileo Galilei</div>
        </div>
        <div class="gallery-item">
            <img src="assets/hawking.jpg" alt="Stephen Hawking">
            <div class="caption">Stephen Hawking</div>
        </div>
        <div class="gallery-item">
            <img src="assets/lovelace.jpg" alt="Ada Lovelace">
            <div class="caption">Ada Lovelace</div>
        </div>
        <div class="gallery-item">
            <img src="assets/turing.jpg" alt="Alan Turing">
            <div class="caption">Alan Turing</div>
        </div>
    </div>

    <footer>
        <button onclick="saveMe()">Klick</button>
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </footer>
</body>
</html>
