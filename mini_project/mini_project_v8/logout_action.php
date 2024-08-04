<?php
     //清除cookie，將過期時間定為之前的時間即可清除
     //將時間設定成過去的時間
     setcookie ( "name", "", time() - 100 , "/"); 
     //將網址導回登入頁
     $URL="login.php"; 
     //header("Location: $URL");
     echo "<script type='text/javascript'>window.location.href='http://192.168.131.161:8080/cloudsystem/login.php'</script>";
?>