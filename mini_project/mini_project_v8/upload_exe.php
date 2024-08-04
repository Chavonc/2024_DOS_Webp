<?php
    header("Content-Type: text/html; charset=utf8");
    //顯示所有錯誤訊息
    ini_set('display_errors','1');
    error_reporting(E_ALL);
    $user=$_COOKIE["name"];//獲得使用者名稱
    $dirname = time().".".rand(0,10);//目錄名
    $dirPath = "/share/".$user."/".$dirname."/";//專門收檔案目錄路徑

    //檢查原檔案名文本
    function isChinese($check)
    {
        $allEn=preg_match("/^[a-zA-Z0-9]+$/", $check);;//全英文
        $allCn = preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $check);//全中文
        $EnNum = preg_match("/^[a-zA-Z0-9]+$/", $check);//英文和數字之組合
        $EnCn=preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u", $check);//英文和中文之組合
        $allSp = preg_match("/[!@#\$%^&*()~`\\\\\/><,.\\';\\[\\]\\{\\}\\+=\\-_]/", $check);//只要包含特殊符號
        if($allEn && !$allSp)
        {
            return 'En';
        }
        elseif($EnNum)
        {
            return 'EnNum';
        }
        elseif($allCn)
        {
            return 'Cn';
        }
        elseif($allSp)
        {
            return 'Special';
        }
        elseif($EnCn)
        {
            return 'EnCn';
        }
    }
    //翻譯成英文
    function translate($text) 
    {
        $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=zh-CN&tl=en&dt=t&q=' . urlencode($text);
        $response = file_get_contents($url);
        if($response!=='En')
        {
            $result = json_decode($response, true);
            if(isset($result[0]) && isset($result[0][0]) && isset($result[0][0][0])) 
            {
                $temp = $result[0][0][0];
                $temp = str_replace(" ", "", $temp);
                $ans = str_replace("'", "", $temp);
                return $ans;
            } 
        }
        else 
        {
            return null;
        }
    }
    //檢測是否有file submit操作 
    if(!isset($_POST['upload']))
    {
        echo "<script type='text/javascript'>alert('請上傳mp3格式的檔案，且檔名請不要包含特殊符號');</script>";
        echo "<script type='text/javascript'>window.location.href='http://192.168.131.161:8080/cloudsystem/home.php'</script>";
        //exit();
    }
    //按下submit檔案
    else
    {
        $file=$_FILES["upload_file"];//獲得上傳之檔案
        $file_name=$file["name"];//原檔案名(ex.111.mp3)        
        $file_suffix=strtolower(pathinfo($file_name,PATHINFO_EXTENSION));//檔案後綴名(mp3)
        $uploadTime=date("Y-m-d H:i:s");//獲得上傳時間
        $file_tmpPath=$file["tmp_name"];//暫存路徑
        $file_size=$file["size"];//檔案大小
        $file_type=$file["type"];//檔案類型
        
        //去除.mp3
        $pattern = '/\.mp3$/';
        $fN = preg_replace($pattern, '', $file_name);
        //echo "<script type='text/javascript'>alert('$fN after replace');</script>";
        $file["error"]=0;
        //檢查檔案名字是否含中文亂碼
        if(isChinese($fN)==='EnNum'||isChinese($fN)==='Cn'||isChinese($fN)==='EnCn')
        {
            $text = translate($fN);
            $file_name=$text.'.mp3';
            // echo "<script type='text/javascript'>alert('$file_name after replace');</script>";
        }
        else
        {
            $file_name=$fN.'.mp3';
        }
        //echo "<script type='text/javascript'>alert('Translation: $fN->$file_name');</script>";
        $ori_file=$dirPath.basename($file_name);//設定檔案路徑
        $ori_file = str_replace("'", "", $ori_file);
        //echo "<script type='text/javascript'>alert('$ori_file after 設定檔案路徑');</script>";


        //echo "<script type='text/javascript'>alert('File Error before check: {$file["error"]}');</script>";
        //上傳檔案時發生錯誤及檢查上傳檔案格式
        if($file["error"]>0 || $file_suffix!="mp3" || $file_type!="audio/mpeg" || isChinese($fN)==='Special')
        {
            // $file_data=json_encode([$file_name,$uploadTime,$file_tmpPath,$file_size,$file_type]);
            // echo "<script type='text/javascript'>alert('$file_data');</script>";
            echo "<script type='text/javascript'>alert('請選擇正確格式的檔案，上傳檔案格式限制為mp3，且檔名請不要包含特殊符號');</script>";
        }
        else
        {
            //建立任務資料夾
            $cmd = "mkdir $dirPath;chmod -Rf 777 $dirPath";
            shell_exec($cmd);//執行命令

            //檢查音檔是否已經分離過存在該user裡
            $dir = "/share/".$user."/";
            $test=scandir($dir);
            array_shift($test);
            array_shift($test); 
            //獲取所有數字資料夾名
            $alltask = array();
            //echo "<script type='text/javascript'>alert('test: ".json_encode($test)."');</script>";
            if(in_array('allList.txt',$test))
            {
                //echo "<script type='text/javascript'>alert('Get inside');</script>";
                $allListFile=fopen($dir.'allList.txt',"r");
                if(!empty($allListFile))
                {
                    while(!feof($allListFile))
                    {
                        $line=fgets($allListFile);
                        if(!empty(trim($line)))
                        {
                            $alltask[]=trim($line);
                        }
                    }
                }
                fclose($allListFile);
                //echo "<script type='text/javascript'>alert('所有數字資料夾名:".json_encode($alltask)."');</script>";
    
                //獲取user的已有的音檔名
                $listArray=array();
                foreach($alltask as $dirData)
                {
                    $list2=scandir($dir.$dirData.'/');
                    array_shift($list2);
                    array_shift($list2);   
                    $check_files=['uploadData.txt','output','computing_1','computing_2','computing_3'];
                    foreach($check_files as $c)
                    {
                        if(in_array($c,$list2))
                        {
                            //若存在就把其從array中刪除
                            unset($list2[array_search($c,$list2)]);
                        }
                    }
                    //刪除後之最終array內容(原音檔)
                    $list2=array_values($list2);
                    $listArray = array_merge($listArray, $list2);
                }
                //$allmp3file=json_encode($listArray);
                //echo "<script type='text/javascript'>alert('所有音檔名:".$allmp3file."');</script>";
                
                //保存user每個音檔名至mp3List.txt(每次上傳前的檔案狀態)
                $mp3File=fopen($dir.'mp3List.txt',"w");
                foreach($listArray as $mp3item)
                {
                    fwrite($mp3File,$mp3item."\n");
                }
                fclose($mp3File);    
                //同一user之音檔名重覆，可拿掉
                if(in_array($file_name,$listArray))
                {
                    echo "<script type='text/javascript'>alert('這個音檔名與您之前的檔案重覆了，請修改需上傳的檔案名');</script>";
                    shell_exec("rm -rf $dirPath");//刪除該資料夾
                    echo "<script type='text/javascript'>window.location.href='http://192.168.131.161:8080/cloudsystem/home.php'</script>";
                }

            }
            if(file_exists($ori_file))
            {
                echo "<script type='text/javascript'>alert('這個音檔已保存在該新資料夾中');</script>";
                shell_exec("rm -rf $dirPath");//刪除該資料夾  
            }
            else
            {
                //檢查檔案大小限制
                if($file_size > 52428800)
                {
                    echo "<script type='text/javascript'>alert('上傳音檔大小限制為50MB');</script>";
                    shell_exec("rm -rf $dirPath");//刪除該資料夾 
                }
                else
                {
                    //將上傳的檔案移動到目標位置
                    if(move_uploaded_file($file_tmpPath,$ori_file))
                    {
                        //保存上傳資訊在txt
                        $Utime=$dirPath."uploadData.txt";
                        $saveD=fopen($Utime,"w");
                        fwrite($saveD,$file_name."\n".$uploadTime."\n".$dirname."\n");
                        echo "<script type='text/javascript'>alert('音檔已成功上傳');</script>";
                        shell_exec("chmod -Rf 777 $dirPath");//設定目錄權限為777
                    }
                    else
                    {
                        echo "<script type='text/javascript'>alert('音檔上傳失敗');</script>";
                        shell_exec("rm -rf $dirPath");//刪除該資料夾            
                    }
                    fclose($saveD);
                }
                
            }
        }
        echo "<script type='text/javascript'>window.location.href='http://192.168.131.161:8080/cloudsystem/home.php'</script>";
    }
?>
