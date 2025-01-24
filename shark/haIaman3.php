<?php
$host = '';
$dbname = '';
$username = '';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

try {
    $stmt = $pdo->prepare("SELECT flag FROM challenges WHERE name = :name LIMIT 1");
    $stmt->execute([':name' => 'shark']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $flag = base64_encode($row['flag']);
    } else {
        $flag = "Flag tidak ditemukan!";
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

header("Refresh: 0.0; url=halaman3.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3</title>
    <style>
        .css {
            color: white; 
        }
    </style>
</head>
<body>
    <div class="css">
        <?= htmlspecialchars($flag); ?>
    </div>
</body>
</html>
