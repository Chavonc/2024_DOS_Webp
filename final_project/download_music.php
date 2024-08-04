<!-- computingNode4, 5, 6 -->

<?php
    //執行 ps 指令以取得進程資訊
    $ps_output = shell_exec('ps aux');
    //分割輸出為行陣列
    $ps_lines = explode("\n", $ps_output);
    //計算行數，減去標題行
    $total_tasks = count($ps_lines) - 2;
    if($total_tasks < 13)
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

        //執行python
        foreach($alltask as $task) 
        {
            //設定計算status檔案路徑
            $state4 = $task."/computing_4";    
            $state5 = $task."/computing_5";
            $state6 = $task."/computing_6";

            // 搜尋資料夾下有沒有.mp3
            $task_files = scandir($task);
            array_shift($task_files);
            array_shift($task_files);
            $matches = preg_grep("/\.mp3$/i", $task_files);

            //存在mp3
            if (!empty($matches)) 
            {
                // echo $matches;
                // echo nl2br("\n");
                continue;
            }
            else
            {
                // echo "No mp3 file";
                // echo nl2br("\n");
                if(!file_exists($state4) && !file_exists($state5) && !file_exists($state6)) 
                {
                    //創建計算status檔案(不同computingNode要用對應的state)
                    // computingNode4: shell_exec("touch $state4");
                    // computingNode5: shell_exec("touch $state5");
                    // computingNode6: shell_exec("touch $state6");
                    shell_exec("touch $state6");
                    sleep(5);
                }


                // computingNode4: file_exists($state4), shell_exec("rm $state5 $stat6");
                // computingNode5: !file_exists($state4) && file_exists($state5), shell_exec("rm $state6");
                // computingNode6: !file_exists($state4) && !file_exists($state5) && file_exists($state6), 
                if (!file_exists($state4) && !file_exists($state5) && file_exists($state6)) 
                { 

                    // if (file_exists($state5)) 
                    // {
                    //     shell_exec("rm $state5");
                    // }
                    // if (file_exists($state6)) 
                    // {
                        // shell_exec("rm $state6");
                    // }

                    $cmd = 'python3 /cloudsystem/download_music.py '.$task;
                    // echo $cmd;
                    shell_exec($cmd);
                    break;
                } 

            }
        }    
    }
?>
