<!-- webServer -->

<?php
    header("Content-Type: text/html; charset=utf8");
    //顯示所有錯誤訊息
    ini_set('display_errors','1');
    error_reporting(E_ALL);
    $user=$_COOKIE["name"];//獲得使用者名稱
    $uploadTime=date("Y-m-d H:i:s");
    $dirname = time().".".rand(0,10);//目錄名
    $dirPath = "/share/".$user."/".$dirname."/";//專門收檔案目錄路徑

    $command = "mkdir $dirPath; chmod 777 $dirPath;";
    shell_exec($command);

    $music_id = $_POST['music_id'];
    $music_name = $_POST['music_name'];

    // echo $music_id."-----".$music_name;

    $music_file = $dirPath."music_info.txt";

    $file = fopen($music_file, 'w');

    // 檢查文件是否成功打開
    if ($file) {
        // 將參數寫入文件並添加換行符
        fwrite($file, $music_id . "\n");
        fwrite($file, $music_name . "\n");
        fwrite($file, $uploadTime . "\n");

        // 關閉文件
        fclose($file);

        echo "任務添加成功！即將為您跳轉至首頁。";
        sleep(5);
        header("refresh:0;url=home.php");
        exit;
    } else {
        echo "任務添加失敗！即將為您跳轉至首頁。";
        sleep(5);
        header("refresh:0;url=home.php");
        exit;
    }
?>