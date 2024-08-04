<!-- webServer -->

<?PHP
    header("Content-Type: text/html; charset=utf8");
    ini_set("display_errors", "On");
    error_reporting(E_ALL);
    
    if(!isset($_POST["submit"]))
    {
        exit("錯誤執行");
    }//檢測是否有submit操作 

    $name = $_POST['name'];//post獲得使用者名稱表單值
    $password = $_POST['password'];//post獲得使用者密碼單值
    
    //如果使用者名稱和密碼都不為空
    if ($name && $password)
    {
        if (is_dir('/share/'.$name)) 
        {
            $info_txt = fopen('/share/'.$name.'/info.txt', 'r');
            $line = fgets($info_txt);
            $info = trim($line);
            if($password == $info)//成功o便跳轉
            {
                // echo $info;
                setcookie("name", $name, time()+3600, "/");
                header("refresh:0;url=home.php");
                exit;
            }
            else//如果錯誤使用js 1秒後跳轉到登入頁面重試;
            {
                echo "使用者名稱或密碼錯誤，3秒鐘後會自動跳轉畫面";
                echo "
                    <script>
                        setTimeout(function(){window.location.href='login.php';},3000);
                    </script>";
                
            }
            fclose($info_txt);
        }
        else 
        {
            echo "尚未成為會員, 請先註冊，3秒鐘後會自動跳轉畫面";
            echo "
            <script>
                setTimeout(function(){window.location.href='signup.php';},3000);
            </script>";
        }
    }
    else//沒填使用者名稱或密碼
    {
        //如果錯誤使用js 1秒後跳轉到登入頁面重試;
        echo "表單填寫不完整，3秒鐘後會自動跳轉畫面";
        echo "
            <script>
                setTimeout(function(){window.location.href='login.php';},3000);
            </script>";
    }

?>