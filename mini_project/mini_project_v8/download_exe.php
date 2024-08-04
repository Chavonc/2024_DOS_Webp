<?php
     header("Content-Type: text/html; charset=utf8");
     //顯示所有錯誤訊息
     ini_set("display_errors", "1");
     error_reporting(E_ALL);
     //file=$filePath.$fileName[0]
     //則share/user/taskID/output/ori_fileName/wav檔
     $filePath=$_GET['file'];
     //檢測是否存在 
     if(!file_exists($filePath))
     {
          echo('要求下載之檔案並不存在.');
          exit();
     }
     else
     {
          //$task=/share/user/taskID
          //2stems -o ' . $task . '/output ' . $task.'/'.$music[0];

          $fileName=explode('/',$filePath);
          //spleeter做法
          //路徑: 0->1->/share/user/taskID/output/ori_fileName/wav檔
          $final_name=$fileName[5].'_'.$fileName[6];
          Header("Content-Type: "."audio/x-wav");
          Header("Content-Description: File Transfer");
          Header("Content-Transfer-Encoding: binary\n");
          Header("Content-Length: ".filesize($filePath));
          Header('Content-Disposition: attachment; filename="'.$final_name.'"');
          readfile($filePath);
          ob_clean();
          flush();
          exit();
     }

?>