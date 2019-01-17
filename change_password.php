<?php
error_reporting (E_ALL ^ E_NOTICE); 
$proses = $_POST["s1"];
//$ip = '10.10.60.2';
$old_name = $_POST["myusername"];
$old_password = $_POST["oldpassword"];
$new_password = $_POST["newpassword"];
$retype_new_password = $_POST["retypenewpassword"];
$cmmnd ="=password=";
$cmmnd .=$new_password;

if(!empty($proses))
{
	if ((!empty($old_name)) && (!empty($old_password)) && (!empty($new_password)))
	{
		require('routeros_api.php');

		$API = new routeros_api();
		$API->debug = false;

		//Router Userman A
		if ($API->connect('100.100.60.20', 'username', 'password')){
			$API->write('/ip/hotspot/user/getall',false);
			$API->write('?name='.$old_name);
			$READ = $API->read(false);
			$ARRAY = $API->parse_response($READ);
			$NILAI = $ARRAY[0][password];
			if($old_password <> $NILAI)		{
				echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
			}else{
				if($new_password == $retype_new_password){
					$API->write('/ip/hotspot/user/set',false);
					$API->write('=.id='.$old_name ,false);
					$API->write('=name='.$old_name ,false);
					$API->write($cmmnd);

					$READ = $API->read(false);
					$ARRAY = $API->parse_response($READ);
					echo '<script>alert("Password on server 1 changed successfully!");</script>';
				}else{
					echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
				}
			}
			$API->disconnect();
		}else{
		  echo '<script>alert("Server not found! Please contact your IT administrator."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
		  $API->disconnect();
		}
		
		//Router Userman B
		if ($API->connect('100.100.60.60', 'username', 'password')){
			$API->write('/ip/hotspot/user/getall',false);
			$API->write('?name='.$old_name);
			$READ = $API->read(false);
			$ARRAY = $API->parse_response($READ);
			$NILAI = $ARRAY[0][password];
			if($old_password <> $NILAI)		{
				echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
			}else{
				if($new_password == $retype_new_password){
					$API->write('/ip/hotspot/user/set',false);
					$API->write('=.id='.$old_name ,false);
					$API->write('=name='.$old_name ,false);
					$API->write($cmmnd);

					$READ = $API->read(false);
					$ARRAY = $API->parse_response($READ);
					echo '<script>alert("Password on server 2 changed successfully!");window.location.href = "http://10.5.5.4/changepassword/";</script>';
				}else{
					echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
				}
			}
			$API->disconnect();
		}else{
		  echo '<script>alert("Server not found! Please contact your IT administrator."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
		  $API->disconnect();
		}
		
		//Router Ruang Guru
		if ($API->connect('100.100.30.30', 'username', 'password')){
			$API->write('/ip/hotspot/user/getall',false);
			$API->write('?name='.$old_name);
			$READ = $API->read(false);
			$ARRAY = $API->parse_response($READ);
			$NILAI = $ARRAY[0][password];
			if($old_password <> $NILAI)		{
				echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
			}else{
				if($new_password == $retype_new_password){
					$API->write('/ip/hotspot/user/set',false);
					$API->write('=.id='.$old_name ,false);
					$API->write('=name='.$old_name ,false);
					$API->write($cmmnd);

					$READ = $API->read(false);
					$ARRAY = $API->parse_response($READ);
					echo '<script>alert("Password on server 3 changed successfully!");window.location.href = "http://10.5.5.4/changepassword/";</script>';
				}else{
					echo '<script>alert("Your password or USER ID is wrong."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
				}
			}
			$API->disconnect();
		}else{
		  echo '<script>alert("Server not found! Please contact your IT administrator."); window.location.href = "http://10.5.5.4/changepassword/";</script>';
		  $API->disconnect();
		}
	}else{
		echo '<script>alert("You did not input any data."); window.location.href = "https://www.google.com";</script>';
	}
}
?>