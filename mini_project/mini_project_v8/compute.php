<?php
    //執行 ps 指令以取得進程資訊
    $ps_output = shell_exec('ps aux');
    //分割輸出為行陣列
    $ps_lines = explode("\n", $ps_output);
    //計算行數，減去標題行
    $total_tasks = count($ps_lines) - 2;
    if($total_tasks<13)
    {
        $dir = '/share';//設置要進行掃描之目錄
        $files = scandir($dir);//獲取目錄下所有子目錄(user_file)
        //用空array儲存user_dir
        $subDirectories = array();    
        //獲取所有user資料夾，要有至少一位user資料夾+cpuinfo資料夾
        if($files>1)
        {
            //遍歷所有文件和子目錄
            foreach ($files as $file) 
            {
                //排除當前及上一级之目錄
                if ($file != '.' && $file != '..') 
                {
                    //判斷是否是所有user_dir
                    if ($file != 'cpuinfo' && $file != 'loadinfo') 
                    {
                        //把user_dir的所有目錄名存入array
                        if (is_dir("$dir/$file")) 
                        {
                            $subDirectories[] = $dir."/".$file;
                        }
                    } 
                }
            }
        }
        // print_r($subDirectories);
        // echo nl2br("\n");
        // 結果：/share/user

        //獲取所有專門檔名TaskFile(隨機數字檔)
        $alltask = array();
        foreach($subDirectories as $dirData) 
        {
            $allListFile=fopen($dirData.'/allList.txt',"r");
            while(!feof($allListFile))
            {
                $line=fgets($allListFile);
                if(!empty(trim($line)))
                {
                    $alltask[]=trim($dirData.'/'.$line);
                }
            }
            fclose($allListFile);
        }
        // print_r($alltask);
        // echo nl2br("\n");
        // 結果：/share/user/taskID

        //alltask按照檔案最後修改日期時間進行排序
        usort($alltask, function($a, $b) 
        {
            //若return之值是正數，代表$a排在$b後面；反之；若為0則兩者相等
            return filemtime($a) - filemtime($b);
        });
        // print_r($alltask);
        // echo nl2br("\n");
        // 結果：/share/user/taskID

        //執行spleeter
        foreach($alltask as $task) 
        {
            //設定計算status檔案路徑
            $state1 = $task."/computing_1";    
            $state2 = $task."/computing_2";
            $state3 = $task."/computing_3";
            //存在output資料夾
            if (is_dir($task.'/'.'output')) 
            {
                //echo $task . " finish\n";
                continue;
            }
            else
            {
                if(!file_exists($state1) && !file_exists($state2) && !file_exists($state3)) 
                {
                    //創建計算status檔案(不同computingNode要用對應的state)
                    // computingNode1: shell_exec("touch $state1");
                    // computingNode2: shell_exec("touch $state2");
                    // computingNode3: shell_exec("touch $state3");
                    shell_exec("touch $state3");
                    sleep(5);
                }


                // computingNode1: file_exists($state1), shell_exec("rm $state2 $state3");
                // computingNode2: !file_exists($state1) && file_exists($state2), shell_exec("rm $state3");
                // computingNode3: !file_exists($state1) && !file_exists($state2) && file_exists($state3), 
                if (!file_exists($state1) && !file_exists($state2) && file_exists($state3)) 
                { 

                    // if (file_exists($state2)) 
                    //{
                    //     shell_exec("rm $state2");
                    // }
                    // if (file_exists($state3)) 
                    //{
                        // shell_exec("rm $state3");
                    // }

                    //掃描task裡的(音檔及uploadData.txt)
                    $files = scandir($task);
                    array_shift($files);
                    array_shift($files);
                    //若存在txt及computing檔，在array中拿掉它們，只抓取音檔
                    $unnecessary_files=['uploadData.txt','computing_1','computing_2','computing_3'];
                    foreach($unnecessary_files as $f)
                    {
                        if(in_array($f,$files))
                        {
                            unset($files[array_search($f,$files)]);
                        }
                    }
                    $music=array_values($files);
                    print_r($music);
                    echo nl2br("\n");
                    $cmd = 'spleeter separate -p spleeter:2stems -o ' . $task . '/output ' . $task.'/'.$music[0];
                    shell_exec($cmd);
                    // print_r($cmd);
                    // echo nl2br("\n");
                    break;
                } 

            }
        }    
    }
?>
