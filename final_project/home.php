<!-- webServer -->
<!-- Notice: change ngrok_link -->
<?php
     if ($_COOKIE["name"] == "") 
     {
          header("refresh:0;url=login.php");
          exit;
     }

     $ngrok_link = 'https://2140-118-150-119-223.ngrok-free.app';
?>

<!doctype html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>上載音檔</title>
     <link rel="stylesheet" href="home.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
     <meta http-equiv="refresh" content="20"><!-- 20sec自動刷新 -->
     <script>
     function adjustHeight1() 
     {
          var table1 = document.querySelector('.relative5 table');
          var rowCount1 = table1.rows.length;
          var heightToAdd1 = rowCount1 * 30; 
          var element1 = document.querySelector('.relative10');
          element1.style.height = (500 + heightToAdd1) + 'px';
     }
     window.onload = adjustHeight1;
     </script>
     <script>
     function conf()
     {
          if(confirm("確認要刪除該工作嗎?"))
          {
               return true;
          }
          return false;     
     }
    </script>
    <style>
     button[id=logout],[id=info]
     {
          border-radius: 10%;
          width:100px;
          height:35px;
          background-color:#6495ED;
          color:white;
     }  
     button[id=download_voice],[id=download_instrumental],[id=download_original]
     {
          border-radius: 10%;
          width:100px;
          height:35px;
          background-color:green;
          color:white;
     }
     button[id=terminate]
     {
          border-radius: 10%;
          width:100px;
          height:35px;
          background-color:red;
          color:white;
     }
     button[id=upload]
     {
          text-align:center;
     }        
     input[id=uploadFile]
     {
          text-align:center;
     }   
     table[id=home]
     {
          width:90%;
          height:100%;
          text-align:center;
          background-color:#E0FFFF;
     }
     table[id=showTask]
     {
          width:100%;
          border:1px black solid;
          margin:auto;
          text-align:center;
     }
     table [id=showTask] tr td 
     {
          height: auto;
     }
     table[id=content]
     {
          width:80%;
          align:center;
          margin:auto;
          text-align:center; 
          background-color:#E0FFFF;
     }
     table[id=content2]
     {
          width:90%;
          text-align:center; 
          align:center;
          margin:auto;
          background-color:#E0FFFF;
     }
     .relative10
     {
          position: relative;
          margin:auto;
          left: 50px;
          width: 1050px;
          height: 500px;
     }
     .relative2
     {
          position: absolute;
          top: 0%;
          left:-5%;
          width: 100%;
          height: 50px;
     }
     .relative3
     {
          position: absolute;
          top: 7%;
          left:0%;
          width:90%;
          height: 30px;
     }
     .relative5
     {
          position: absolute;
          top: 15%;
          left:5%;
          width:80%;
          /*height: auto;*/
     }
    </style>
</head>

<body style="background-color:#FFE4C4;">
<div class="relative10">
    <table id="home">
        <tr>
               <td>
                    <div class="relative2">
                         <table id="content">
                              <tr>
                                   <td style="text-align:left">
                                        <?php
                                             $user = $_COOKIE['name'];
                                             echo "Welcome ".$user;
                                        ?>
                                   </td>
                                   <td style="text-align:right">
                                        <a href="queuingInfo.php">
                                             <button id="info" type="button" class="btn btn-outline-light">Info</button>
                                        </a>
                                        <a href="logout_action.php">
                                             <button id="logout" type="button" class="btn btn-outline-light">登出</button>
                                        </a>
                                   </td>
                              </tr>
                         </table>
                    </div>
                    <div class="relative3">
                         <table id="content2">
                              <tr style="text-align:center">
                                   <td colspan="2">
                                        <form class="input-group" method="post" action="upload_exe.php" enctype="multipart/form-data" style="width:auto;">
                                             <input type="file" id="uploadFile" name="upload_file" accept="audio/*" class="form-control" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                             <button type="submit" id="upload" name="upload" class="btn btn-outline-secondary">Upload</button>
                                        </form>
                                   </td>              
                              </tr>
                         </table>
                    </div>
                    <div class="relative5">
                         <table id="showTask" rules="all">
                              <?php
                                   $dirPath="/share/".$user."/";
                                   $list=scandir($dirPath);
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
                                   //echo '<tr><td>$test '.json_encode($test).'</td><tr>';
                              ?>
                              <?php
                                   if(json_encode($test)=='[]')
                                   {
                                        echo '<tr><th colspan="5">Please upload your audio file(mp3)</th></tr>';
                                   }
                                   else
                                   {
                                        echo '<tr><th>Task</th><th>Status</th><th>Download(Original)</th><th>Download(Voice)</th><th>Download(Instrumental)</th><th>Terminate</th></tr>';
                                        $writePath=$dirPath."allList.txt";
                                        $save=fopen($writePath,"w");
                                        //user的所有數字專門檔名寫入txt檔
                                        foreach($list as $item)
                                        {
                                             if(is_numeric($item))
                                             {
                                                  fwrite($save,$item."\n");
                                             }
                                        }
                                        fclose($save);
                                                            
                                        //取得每個數字專門檔名
                                        $allListFile=fopen($writePath,"r");
                                        if(!empty($allListFile))
                                        {
                                             while(!feof($allListFile))
                                             {
                                                  $line=fgets($allListFile);
                                                  if(!empty(trim($line)))
                                                  {
                                                       $dirArray[]=trim($line);
                                                  }
                                             }
                                        }
                                        fclose($allListFile);
                                        //echo json_encode($dirArray);

                                        //取得專門檔的列表(音檔,uploadData.txt)
                                        foreach($dirArray as $dirData)
                                        {
                                             $uploadData_Path=$dirPath.$dirData."/";
                                             #echo $dirData;
                                             //掃描該目錄包含的檔及資料夾
                                             $list2=scandir($uploadData_Path);
                                             //不要掃描之後的某些格式
                                             array_shift($list2);
                                             array_shift($list2);   
                                             //檢查是否存在那些檔案，只保留原音檔
                                             $check_files=['uploadData.txt','output','computing_1','computing_2','computing_3', 'computing_4', 'computing_5', 'computing_6', 'music_info.txt'];
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
                                             //確認user的全部fileName
                                             $fileNameArray=json_encode($list2[0],JSON_UNESCAPED_SLASHES);
                                             $fileNameArray = trim($fileNameArray, '"');

                                             // 尚未從youtube中下載mp3檔案
                                             $youtube_state = 'did';
                                             if($fileNameArray=='null') {
                                                  $temp = $uploadData_Path.'music_info.txt';
                                                  $lines = file($temp);
                                                  $fileNameArray = $lines[1].'.mp3';
                                                  $youtube_state = 'yet';
                                             }

                                             echo "<tr><td>".$fileNameArray."</td>";

                                             //確認status
                                             $status = '';//用三個computing檔來當各自的status表
                                             //mp3還沒好
                                             if(($youtube_state == 'yet') && (is_file($dirPath.$dirData.'/computing_4') || is_file($dirPath.$dirData.'/computing_5') || is_file($dirPath.$dirData.'/computing_6'))) {
                                                  $status = 'Youtube';
                                             }
                                             //有computing_，但還沒生成output
                                             elseif((is_file($dirPath.$dirData.'/computing_1') || is_file($dirPath.$dirData.'/computing_2') || is_file($dirPath.$dirData.'/computing_3')) && !is_dir($dirPath.$dirData.'/output') && $youtube_state == 'did') 
                                             {
                                                  //tackle with cpu?
                                                  $status = 'Spleeter';
                                             }
                                             //若已生成output資料夾
                                             elseif (is_dir($dirPath.$dirData.'/output')) 
                                             {
                                                  $output_dir = scandir($dirPath.$dirData.'/output');
                                                  array_shift($output_dir);
                                                  array_shift($output_dir);
                                                  //從是否已生成第一個分離音檔來檢查
                                                  $output_files = scandir($dirPath.$dirData.'/output/'.$output_dir[0]);
                                                  array_shift($output_dir);
                                                  array_shift($output_dir);
                                                  //若少於2個檔案則還沒分離完
                                                  if (count($output_files) < 2) 
                                                  {
                                                       $status = 'Spleeter';
                                                  }
                                                  else
                                                  {
                                                       $status = 'Finished';
                                                  }
                                             }
                                             //沒有computing檔和output資料夾，且還沒下載youtube檔案
                                             else 
                                             {
                                                  $status = 'Queuing';
                                             }
                                             //print_r($output_files);
                                             //前端顯示Status資訊
                                             echo "<td>".$status."</td>";

                                             if ($youtube_state == 'did') {
                                                  $link = $ngrok_link.'/cloudsystem/download_original.php?file=';
                                                  $filePath=$link.$dirPath.$dirData.'/'.$fileNameArray;
                                                  echo '<td><a href='.$filePath.'><button id="download_original" type="button" class="btn btn-outline-light" >download</button></a></td>';
                                             }
                                             else {
                                                  echo '<td></td>';
                                             }

                                             //下載及刪除
                                             //下載路徑: /share/user/taskID/output/ori_fileName/
                                             //刪除路徑: /share/user/taskID
                                             if ($status == "Finished") 
                                             {
                                                  // $link='http://192.168.131.161:8080/cloudsystem/download_exe.php?file=';
                                                  $link = $ngrok_link.'/cloudsystem/download_exe.php?file=';
                                                  $filePath=$dirPath.$dirData.'/output/';
                                                  $fileName=explode(".", $fileNameArray);
                                                  $downloadPath=$link.$filePath.$fileName[0].'/';
                                                  echo '<td><a href='.$downloadPath.'vocals.wav><button id="download_voice" type="button" class="btn btn-outline-light" >download</button></a></td>';
                                                  echo '<td><a href='.$downloadPath.'accompaniment.wav><button id="download_instrumental" type="button" class="btn btn-outline-light">download</button></a></td>';
                                                  echo '<td></td></tr>';
                                             }
                                             elseif ($status == "Computing") 
                                             {
                                                  echo '<td>Waiting</td>';
                                                  echo '<td>Waiting</td>';
                                                  echo '<td></td></tr>';
                                             }
                                             elseif ($status == "Queuing") 
                                             {
                                                  // $link='http://192.168.131.161:8080/cloudsystem/terminate_exe.php?file=';
                                                  $link = $ngrok_link.'/cloudsystem/terminate_exe.php?file=';
                                                  $terminatePath=$link.$dirPath.$dirData;
                                                  echo '<td></td>';
                                                  echo '<td></td>';
                                                  echo '<td><a href='.$terminatePath.' onclick="return conf();"><button id="terminate" type="button" class="btn btn-outline-light">terminate</button></a></td></tr>';
                                             }
                                             elseif($status == "Terminated")
                                             {
                                                  echo '<td>X</td>';
                                                  echo '<td>X</td>';
                                                  echo '<td>X</td></tr>';
                                             }
                                             else
                                             {
                                                  echo '<td></td>';
                                                  echo '<td></td>';
                                                  echo '<td></td></tr>';
                                             }
                                        }
                                   }
                              ?>
                         </table>
                   </div>
               </td>
        </tr>
     </table>
</div>
</body>
</html>