<!-- webServer -->

<?php
     header("Content-Type: text/html; charset=utf8");
     //顯示所有錯誤訊息
     ini_set("display_errors", "1");
     error_reporting(E_ALL);
     $filePath=$_GET["file"];
     $user=$_COOKIE["name"];//獲得使用者名稱
     //檢測是否存在該dir 
     if(!is_dir($filePath))
     {
          echo("要刪除之工作資料夾並不存在.");
          exit();
     }
     else
     {
          shell_exec('rm -rf '.$filePath);
          $dir = "/share/".$user."/";
          $list=scandir($dir);
          array_shift($list);
          array_shift($list);
          $unnecessary_files=['info.txt','allList.txt'];
          foreach($unnecessary_files as $f)
          {
               if(in_array($f,$list))
               {
                    unset($list[array_search($f,$list)]);
               }
          }
          $test=array_values($list);
          $taskName=fopen($dir.'allList.txt',"w");
          foreach($list as $item)
          {
               if(is_numeric($item))
               {
                    fwrite($taskName,$item."\n");
               }
          }
          fclose($taskName);    

          header("refresh:0;url=home.php");
          exit;
     }
 
?>