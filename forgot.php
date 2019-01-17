<?php
error_reporting(E_ALL ^ E_NOTICE);
$emailUser    = $_POST['myemail'];
$old_name     = $emailUser;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$alamatReset  = 'http://localhost/forgot/Change%20Password%20User%20Hotspot%20_%20SMK%20Telkom%20Malang.html';//Masukkan Url Forgot Password
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
if ($conn->connect_error) {
  echo "<script>var reload = prompt('Koneksi Error : $conn->connect_error . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
} else {
  echo "Connected successfully";
  $sql    = "SELECT * FROM `limit` WHERE user= '$emailUser'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<script>var reload = prompt('Gagal Update Password . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
  } else {
    $cmmnd = "=password=";
    $cmmnd .= $new_password;
    require('routeros_api.php');
    require 'sendgrid-php.php';
    $newPass = $new_password;
    echo 'Password Anda ' . $newPass . '     ';
    $API        = new routeros_api();
    $API->debug = false;
    if ($API->connect('192.168.43.18', 'admin', 'admin')) { //Silahkan di edit untuk user router mikrotik
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
      echo '<script>alert("Password on server 1 changed successfully!");</script>';
      //SendGrid
      $email = new \SendGrid\Mail\Mail();
      $email->setFrom("tefa@gmail.com", "Wibi");
      $email->setSubject("Sending with SendGrid is Fun");
      $email->addTo("brian.rofiq@gmail.com", "Brian");
      $email->addContent("text/html", "<html><p>Password Anda Adalah : " . $newPass . "</p> <strong>tefa loves you</strong></html>");
      $sendgrid = new \SendGrid('SG.8hXB0F5XTK2hAS7evE3qSA.0JN-CETYD2y8cADjqxvmhmPYqk0Fdlq8HAYSXB3I7qA');
      try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        //SQL
        $sql = "INSERT INTO `limit`(`user`) VALUES ('$emailUser')";
        if ($conn->query($sql) === TRUE) {
          //echo "New record created successfully";
        } else {
          // echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
      }
      catch (Exception $e) {
        echo "<script>var reload = prompt('Tidak bisa mengirim email Password anda $newPass . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
      }
      $API->disconnect();
    } else {
      echo "<script>var reload = prompt('Tidak bisa login ke router . Tekan Ok Untuk Reload');

    if (reload != null) {
        window.location.href = '$alamatReset';
    }</script>";
    }
  }
}