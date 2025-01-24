<?php
session_start();

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

$challenge_name = "script";
$required_level = 4;

if ($level < $required_level) {
    header("Location: ../no_access.php");
    exit();
}

$flag = "root{tHis_is_A_r3FlecTed_xSs}";
$message = "";
$isFlagDisplayed = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['message'])) {
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

        $xss_patterns = [
            '<script>', '<img', '<iframe', 'onerror', 'onclick', 'onload', 'javascript:'
        ];

        foreach ($xss_patterns as $pattern) {
            if (stripos($message, $pattern) !== false) {
                $isFlagDisplayed = true;
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>script</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        .news {
            background: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .news h2 {
            color: #007bff;
        }

        .form-container {
            margin: 20px auto;
            max-width: 800px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            position: absolute;
            bottom: 10px;
            text-align: center;
            width: 100%;
            color: #888;
            font-size: 14px;
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
    margin-bottom: 20px;

    display: block;
    margin-left: auto;
    margin-right: auto;
}

.hint-button:hover {
    background-color: #e67e22;
}


.hint-container {
    margin-top: 20px;
    font-size: 14px;
    display: none; 
    color: #f39c12;
    text-align: center;
    background-color: #white;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    width: fit-content;

</style>


    </style>
<script>
    function showHint() {
        var hint = document.getElementById('hint');
        hint.style.display = 'block';
    }
</script>

</head>
<body>
    <h1>Football News</h1>

    <div class="news">
        <h2>Hasil Liverpool vs Manchester United: Skor 7-0</h2>
	<button class="hint-button" onclick="showHint()">Show Hint</button>

	<div id="hint" class="hint-container" style="display:none;">
	    <p>I don't properly sanitize the input.</p>
	</div>

        <div class="button-container">
        <button onclick="window.location.href='../welcome.php';">Back</button>
        <button onclick="window.location.href='../input_flag.php';">Submit Flag</button>
        </div>

        <img src="assets/skor.jpg" alt="Liverpool Bantai Manchester United" style="max-width: 100%; height: auto; border-radius: 8px; margin-top: 10px;">
        <p>
            Liverpool tanpa ampun menghancurkan Manchester United di laga pekan ke-26 Premier League 2022/2023 di Stadion Anfield, Minggu (05/03/2023) malam WIB.
        </p>
        <p>
            Pertandingan berlangsung sangat menarik. Sebab awalnya kedua tim sama-sama saling menyerang dan menciptakan sejumlah peluang. Namun Liverpool kemudian bisa menguasai jalannya laga.
        </p>
        <p>
            Tiga pemain Liverpool mencetak brace. Mereka adalah Mohamed Salah, Cody Gakpo, dan Darwin Nunez. Satu gol lagi disumbangkan Roberto Firmino.
        </p>
        <p>
            Dengan hasil ini Liverpool kini naik ke peringkat lima klasemen sementara Premier League 2022/2023 dengan raihan 42 angka dari 25 laga. Sementara Man United tetap di peringkat tiga dengan raihan 49 angka.
        </p>
        
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fringilla, turpis nec pulvinar vestibulum, tortor risus interdum odio, eget interdum felis lorem eget urna. Phasellus condimentum sapien vitae ipsum luctus, non tincidunt ligula pretium. Nulla facilisi. Maecenas interdum, nisl sed facilisis tincidunt, quam nunc gravida purus, eget ullamcorper arcu odio id metus. Aenean fermentum tellus vel mauris efficitur, eget dictum lectus consequat.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent dapibus ligula in fermentum varius. Suspendisse ac fringilla arcu. Integer ac tellus eget sapien euismod cursus. Aenean bibendum nisi in urna iaculis venenatis. Proin sagittis nisl ut nisl sollicitudin fermentum. Phasellus tincidunt, mauris id posuere efficitur, orci dui feugiat turpis, quis consectetur mauris orci id libero.
        </p>
        <p>
            Quisque vitae interdum lectus. Suspendisse sagittis quam ac augue tristique varius. Vestibulum suscipit lacus sit amet diam suscipit, at congue massa aliquet. Nam auctor velit ac leo venenatis, quis malesuada turpis venenatis. Integer tincidunt neque eget felis sodales, nec dictum nisi dictum.
        </p>
        <p>
            Sed consequat, nibh in ultricies scelerisque, felis lorem fermentum elit, at iaculis risus justo sit amet ligula. Nam tempor lacus a felis congue tristique. Vestibulum vitae tincidunt nibh, vel consequat purus. Etiam venenatis nisl a justo dictum, quis pretium enim facilisis.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fringilla, turpis nec pulvinar vestibulum, tortor risus interdum odio, eget interdum felis lorem eget urna. Phasellus condimentum sapien vitae ipsum luctus, non tincidunt ligula pretium. Nulla facilisi. Maecenas interdum, nisl sed facilisis tincidunt, quam nunc gravida purus, eget ullamcorper arcu odio id metus. Aenean fermentum tellus vel mauris efficitur, eget dictum lectus consequat.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent dapibus ligula in fermentum varius. Suspendisse ac fringilla arcu. Integer ac tellus eget sapien euismod cursus. Aenean bibendum nisi in urna iaculis venenatis. Proin sagittis nisl ut nisl sollicitudin fermentum. Phasellus tincidunt, mauris id posuere efficitur, orci dui feugiat turpis, quis consectetur mauris orci id libero.
        </p>
        <p>
            Quisque vitae interdum lectus. Suspendisse sagittis quam ac augue tristique varius. Vestibulum suscipit lacus sit amet diam suscipit, at congue massa aliquet. Nam auctor velit ac leo venenatis, quis malesuada turpis venenatis. Integer tincidunt neque eget felis sodales, nec dictum nisi dictum.
        </p>
        <p>
            Sed consequat, nibh in ultricies scelerisque, felis lorem fermentum elit, at iaculis risus justo sit amet ligula. Nam tempor lacus a felis congue tristique. Vestibulum vitae tincidunt nibh, vel consequat purus. Etiam venenatis nisl a justo dictum, quis pretium enim facilisis.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus fringilla, turpis nec pulvinar vestibulum, tortor risus interdum odio, eget interdum felis lorem eget urna. Phasellus condimentum sapien vitae ipsum luctus, non tincidunt ligula pretium. Nulla facilisi. Maecenas interdum, nisl sed facilisis tincidunt, quam nunc gravida purus, eget ullamcorper arcu odio id metus. Aenean fermentum tellus vel mauris efficitur, eget dictum lectus consequat.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent dapibus ligula in fermentum varius. Suspendisse ac fringilla arcu. Integer ac tellus eget sapien euismod cursus. Aenean bibendum nisi in urna iaculis venenatis. Proin sagittis nisl ut nisl sollicitudin fermentum. Phasellus tincidunt, mauris id posuere efficitur, orci dui feugiat turpis, quis consectetur mauris orci id libero.
        </p>
        <p>
            Quisque vitae interdum lectus. Suspendisse sagittis quam ac augue tristique varius. Vestibulum suscipit lacus sit amet diam suscipit, at congue massa aliquet. Nam auctor velit ac leo venenatis, quis malesuada turpis venenatis. Integer tincidunt neque eget felis sodales, nec dictum nisi dictum.
        </p>
      

        <?php
        if ($isFlagDisplayed) {
            echo "<h3>Flag: $flag</h3>";
        } else {
            echo "<p>Your message: $message</p>";
        }
        ?>
        
        <div class="form-container">
            <form method="POST" action="">
                <textarea name="message" placeholder="Enter your message here"></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <!-- <div class="footer">
        <p>&copy; 2024 Muhammad Qurais Sihab</p>
    </div> -->
</body>
</html>
