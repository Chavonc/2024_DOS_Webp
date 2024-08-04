<!-- webServer -->

<?php
    $user = $_GET['user_id'];
    echo $user." welcome!";
    echo nl2br("\n");
    setcookie("name", $user, time()+3600, "/");

    $dirPath = "/share/";
    $userPath = $dirPath.$user;
    
    if (is_dir($userPath)) {
        echo "已為您登入成功！正在為您跳轉畫面，請稍等3秒鐘";
    } else {
        $command = "mkdir $userPath";
        shell_exec($command);
        echo "已為您註冊成功！正在為您跳轉畫面，請稍等3秒鐘";
    }   
    
    sleep(5);
    header("refresh:0;url=home.php");
    exit;
?>