<?php 
    header("Content-Type: text/html; charset=utf8");
    ini_set("display_errors", "On");
    error_reporting(E_ALL);
    //判斷是否有submit操作
    if(!isset($_POST['submit']))
    {
        exit("錯誤執行");
    }

    $name=$_POST['name'];//post獲取表單裡的name
    $password=$_POST['password'];//post獲取表單裡的password

    $dir_path = "/share/".$name;

    if(is_dir($dir_path)) 
    {
        echo "您已註冊，請登入！";
        echo "
            <script>
                setTimeout(function(){window.location.href='login.php';},3000);
            </script>";
    }
    else 
    {
        mkdir($dir_path, 0777, true);
        $filename = $dir_path.'/info.txt'; //設定路徑加上要輸出的名稱
        if(@$fp = fopen($filename, 'w+'))
        {
           //寫入資料
            fwrite($fp, $password);
            fclose($fp);
        }
        echo "註冊成功，3秒鐘後會自動跳轉畫面";
        echo "
            <script>
                setTimeout(function(){window.location.href='login.php';},3000);
            </script>";
    }

?>