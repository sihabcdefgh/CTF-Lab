<?php

if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
    header("X-CTF-Flag: root{C0n9ra7ulat1on5_u_gEt_tH3_Flag}");
    echo "Flag telah ditampilkan di header. Gunakan 'HEAD' untuk melihatnya.";
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GETme</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #2c2c2c;
                flex-direction: column;
            }

            .image-container {
                text-align: center;
            }

            img {
                max-width: 25%;
                height: auto;
                border: 2px solid #000;
                margin-bottom: 20px;
            }
	    .hint-button {
            background-color: #f1c40f;
            color: #2c2c2c;
}

.hint-container {
            margin-top: 20px;
            font-size: 14px;
            display: none; 
            color: #f39c12;
}


            h1 {
                color: #007bff;
            }

            .button-container {
                margin-top: 20px;
                display: flex;
                justify-content: center; 
                align-items: center; 
                gap: 10px;
            }

            .home-btn, .submit-btn {
                padding: 10px 20px;
                font-size: 16px;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 150px;
                text-align: center;
            }

            .home-btn {
                background-color: #007bff;
            }

            .home-btn:hover {
                background-color: #0056b3;
            }

            .submit-btn {
                background-color: #28a745;
            }

            .submit-btn:hover {
                background-color: #218838;
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
<script>
        function showHint() {
            var hint = document.getElementById('hint');
            hint.style.display = 'block'; 
        }
</script>
    </head>
    <body>

        <div class="image-container">
            <img src="assets/1.jpg" alt="">
            <p class="hint"></p>
            <div class="button-container">

                <a href="../welcome.php">
                    <button class="home-btn">Back</button>
                </a>
                <a href="../input_flag.php">
                    <button class="submit-btn">Submit Flag</button>
                </a>

            </div>
        </div>
        <div class="footer">
            <p>&copy; 2024 Muhammad Qurais Sihab</p>
        </div>

    </body>
    </html>
    <?php
}
?>
