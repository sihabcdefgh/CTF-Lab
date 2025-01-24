<?php
$upload_dir = 'uploads/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

    $max_size = 90 * 1024;
    if ($file_size > $max_size) {
        $error_message = "Error: Gambar tidak boleh lebih dari 90KB!";
    } elseif (!in_array($file_ext, $allowed_extensions)) {
        $error_message = "Error: Hanya file .jpg, .jpeg, .png, .gif yang diperbolehkan!";
    } else {
        $destination = $upload_dir . $file_name;
        if (move_uploaded_file($file_tmp, $destination)) {
            $success_message = "File berhasil diunggah: <a href='$destination'>$destination</a><br>";
        } else {
            $error_message = "Error: Gagal mengunggah file.";
        }
    }
} else {
    $error_message = "Error: Tidak ada file yang diunggah.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camouflage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../qQQqQQ.png');
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 16px;
            color: #666;
        }
        .message {
            margin-top: 20px;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
            font-size: 16px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Status</h1>

        <?php if (isset($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <div class="message success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <a href="dashboard.php" class="button">Back</a>
    </div>
</body>
</html>
