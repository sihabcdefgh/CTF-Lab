<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$pdo = new PDO("mysql:host=;dbname=", "", "");

$userId = $_GET['user_id'];
$query = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$query->bindParam(':id', $userId);
$query->execute();

$user = $query->fetch(PDO::FETCH_ASSOC);
$recipientEmail = $user['email'];

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = '';
    $mail->Password   = '';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 587;

    $mail->setFrom('', '');
    $mail->addAddress($recipientEmail);
    $mail->Subject = 'Laporan Mingguan';
    $mail->Body    = 'Halo, ini adalah email tes untuk memastikan pengaturan SMTP berjalan dengan baik.';

    $mail->send();
    echo 'Email berhasil dikirim.';
} catch (Exception $e) {
    echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
}
?>
