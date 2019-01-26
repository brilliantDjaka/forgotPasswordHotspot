<?php
error_reporting(E_ALL ^ E_NOTICE);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';
$emailUser    = $_POST['myemail'];
$old_name     = $emailUser;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$alamatReset  = 'http://localhost/Forgot%20Password%20Wifi/Change%20Password%20User%20Hotspot%20_%20SMK%20Telkom%20Malang.html';//Masukkan Url Forgot Password
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$new_password = substr(uniqid(), 8);
//SQL CONNECT//
$servername   = "localhost";
$username     = "root";
$password     = "";
$db           = 'log';
// Create connection
$conn      = new mysqli($servername, $username, $password, $db);
// Check connection
$times = date('d-m-Y');
    require('routeros_api.php');
if ($conn->connect_error) {
  echo "<script>var reload = confirm('Koneksi Error : $conn->connect_error . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
} else {
  //echo "Connected successfully";
  $sql    = "SELECT * FROM `limit` WHERE user= '$emailUser' and tanggal = '$times' ";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<script>var reload = confirm('Maaf, anda baru mereset password anda hari ini. Silahkan cek Email . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
  } else {
    $cmmnd = "=password=";
    $cmmnd .= $new_password;
    
    $newPass = $new_password;
    //echo 'Password Anda ' . $newPass . '     ';
    $API        = new routeros_api();
    $API->debug = false;
    if ($API->connect('192.168.43.147', 'admin', 'admin')) { //Silahkan di edit untuk user router mikrotik
      $API->write('/ip/hotspot/user/getall', false);
      $API->write('?name=' . $old_name);
      $READ  = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      $API->write('/ip/hotspot/user/set', false);
      $API->write('=.id=' . $old_name, false);
      $API->write('=name=' . $old_name, false);
      $API->write($cmmnd);
      $READ  = $API->read(false);
      $ARRAY = $API->parse_response($READ);
      //PHP mailer
      try {
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->SMTPDebug = 2;
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = 587;
      $mail->SMTPSecure = 'tls';
      $mail->SMTPAuth = true;
      $mail->Username = "";//silahkan diisi
      $mail->Password = "";//silahkan diisi
      $mail->setFrom('', '');//silahkan diisi
      $mail->addAddress($emailUser, $emailUser);//silahkan diisi
      $mail->Subject = 'RESET PASSWORD';
      $mail->msgHTML("<html><p>RESET USER PASSWORD INTERNER SMK TELKOM MALANG BERHASIL.<br>Password Anda Adalah : <b>" . $newPass . "</b>.<br> Terimakasih<br>Sarpra.</p></html>");
      if (!$mail->send()) {
        echo "<script>var reload = confirm('Tidak bisa mengirim email. Password anda : $newPass . Tekan Ok Untuk Reload') if (reload != null) {window.location.href = '$alamatReset';}</script>";
      } else {
          $sql = "INSERT INTO `limit`(`user`,`tanggal`) VALUES ('$emailUser','$times')";
          $conn->query($sql);
          echo "
                <script>
                 var reload = confirm('Berhasil. Password anda : $newPass . Tekan Ok Untuk Reload');
                 if(reload != null) {
                   window.location.href = '$alamatReset';
                  }
                </script>";

      }
      } catch (Exception $e) {
        echo "<script>var reload = confirm('Tidak bisa mengirim email Password anda $newPass . Tekan Ok Untuk Reload');
        if (reload != null) {window.location.href = '$alamatReset';}</script>";
      }
      
      $API->disconnect();
    } else {

      echo "<script>
           var reload = confirm('Tidak bisa login ke router . Tekan Ok Untuk Reload');
            if(reload != null) {
                window.location.href = '$alamatReset';
            }</script>
            ";
    }
  }
}