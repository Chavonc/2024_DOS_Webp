<!-- webServer -->

<?php
     //清除cookie，將過期時間定為之前的時間即可清除
     //將時間設定成過去的時間
     setcookie ( "name", "", time() - 100 , "/"); 
     //將網址導回登入頁
     header("refresh:0;url=login.php");
     exit;
?>