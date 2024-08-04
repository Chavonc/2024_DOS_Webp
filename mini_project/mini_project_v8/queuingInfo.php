<?php
ini_set('display_errors','1');// 設定 PHP 顯示所有錯誤訊息
error_reporting(E_ALL);
header('refresh: 60;');
if ($_COOKIE["name"] == "") 
{
     echo "<script type='text/javascript'>window.location.href='http://192.168.131.161:8080/cloudsystem/login.php'</script>";
}
?>
<!doctype html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>查看資訊</title>
     <link rel="stylesheet" href="q_info.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
     <meta http-equiv="refresh" content="60"><!-- 60sec自動刷新 -->
     <script>
     function adjustHeight() 
     {
          var table = document.querySelector('.relative3 table');
          var rowCount = table.rows.length;
          var heightToAdd = rowCount * 20; 
          var element = document.querySelector('.relative1');
          element.style.height = (500 + heightToAdd) + 'px';
     }
     window.onload = adjustHeight;
     </script>
     <style>
          button[type=submit],[id=logout],[id=home],[id=cpuInfo]
          {
               border-radius: 10%;
               width:100px;
               height:35px;
               background-color:#6495ED;
               color:white;
          }  
          button[id=download_voice],[id=download_instrumental]
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
          table[id=begin]
          {
               width:90%;
               height:100%;
               text-align:center;
               background-color:#E0FFFF;
          }
          table[id=showTask]
          {
               width:90%;
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
          .relative1
          {
               position: relative;
               margin:auto;
               left: 50px;
               width: 1050px;
               height: 450px;
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
               height: auto;
          }
     </style>
</head>

<body style="background-color:#FFE4C4;">
<div class="relative1">
     <table id="begin">
          <tr>
               <td>
                    <div class="relative2">
                         <table id="content" >
                              <tr>
                                   <td style="text-align:left">
                                        查看全部工作資訊                                    
                                   </td>
                                   <td style="text-align:right">
                                        <a href="cpu.php">
                                             <button id="cpuInfo" type="button" class="btn btn-outline-light">CPU</button>
                                        </a>
                                        <a href="home.php">
                                             <button id="home" type="button" class="btn btn-outline-light">Home</button>
                                        </a>
                                        <a href="logout_action.php">
                                             <button id="logout" type="button" class="btn btn-outline-light">登出</button>
                                        </a>
                                   </td>
                              </tr>
                         </table>
                    </div>
                    <div class="relative3">
                         <table id="showTask" rules="all">
                              <tr><th>Number</th><th>User</th><th>UploadTime</th><th>Task</th><th>Status</th><th>Running Node</th></tr>
                                   <?php
                                        // 抓所有使用者
                                        $dirPath="/share/";
                                        $list=scandir($dirPath);
                                        array_shift($list);
                                        array_shift($list);
                                        $no_need=['cpuinfo','allList.txt','userList.txt'];
                                        foreach($no_need as $a)
                                        {
                                             if(in_array($a,$list))
                                             {
                                                  unset($list[array_search($a,$list)]);
                                             }
                                        }
                                        $list=array_values($list);
                                        //echo '<tr><td>$list: '.json_encode($list).'</td><tr>';//all_user
                                        // print_r($list);
                                        // Array ( [0] => furry [1] => furry2 )
                                   ?>
                                   <?php
                                        //寫入所有user名之list
                                        $userPath=$dirPath."userList.txt";
                                        $userList=fopen($userPath,"w");
                                        foreach($list as $item)
                                        {
                                             fwrite($userList,$item."\n");
                                        }
                                        fclose($userList);
                                        //抓所有使用者的allList.txt(專門檔名)
                                        $temp_dir = [];
                                        foreach ($list as $user_list) 
                                        {
                                             $user_task = scandir('/share/'.$user_list.'/');
                                             array_shift($user_task);
                                             array_shift($user_task);          
                                             $no=['info.txt'];
                                             foreach($no as $w)
                                             {
                                                  if(in_array($w,$user_task))
                                                  {
                                                       unset($user_task[array_search($w,$user_task)]);
                                                  }
                                             }
                                             $user_task=array_values($user_task);
                                             //echo '<tr><td>$user_task2: '.json_encode($user_task).'</td><tr>';
                                             if(json_encode($user_task)=='[]' || json_encode($user_task)=='["allList.txt"]'|| json_encode($user_task)=='["allList.txt","mp3List.txt"]')
                                             {
                                                  echo '<tr><th colspan="6">User: '.$user_list.' does not upload mp3 file yet</th></tr>';
                                             }
                                             else
                                             {
                                                  if (in_array('allList.txt', $user_task)) 
                                                  {
                                                       $user_alltask_list = '/share/'.$user_list.'/allList.txt';
                                                       if(file_exists($user_alltask_list)) 
                                                       {
                                                            array_push($temp_dir, $user_alltask_list);                                                  
                                                       }
                                                  }
                                             }
                                        }
                                        // print_r($temp_dir);
                                        // Array ( [0] => /share/furry/allList.txt [1] => /share/furry2/allList.txt )

                                        // 利用所有使用者的allList.txt去抓他們的任務資料夾
                                        $queuing_tasks_list = [];
                                        if(count($temp_dir) != 0) 
                                        {
                                             foreach ($temp_dir as $txt) 
                                             {
                                                  $file = fopen($txt, "r"); 
                                                  $txt = explode( "/", $txt);
                                                  // print_r($txt);
                                                  // Array ( [0] => [1] => share [2] => furry [3] => allList.txt )
                                                   while(!feof($file)) 
                                                  {
                                                       $line = fgets($file);
                                                       $line = str_replace("\n", "", $line);
                                                       // echo $line.nl2br("\n");
                                                       if ($line !==  "") 
                                                       {
                                                            $line = $dirPath.$txt[2].'/'.$line.'/';
                                                            array_push($queuing_tasks_list, $line);
                                                       }
                                                  }
                                             }
                                             // 將queuing_tasks_list按照檔案日期排序
                                             usort($queuing_tasks_list, function($a, $b) 
                                             {
                                                  return filemtime($a) - filemtime($b);
                                             });
                                             // print_r($queuing_tasks_list);
                                             // Array ( [0] => /share/furry/1713766969.5/ )

                                             if(count($queuing_tasks_list) != 0) 
                                             {
                                                  $casenum = 1;
                                                  foreach($queuing_tasks_list as $task) 
                                                  {
                                                       $info = explode("/" ,$task);
                                                       // print_r($info);
                                                       // Array ( [0] => [1] => share [2] => furry [3] => 1713766969.5 [4] => )

                                                       $task_files = scandir($task);
                                                       array_shift($task_files);
                                                       array_shift($task_files);
                                                       // print_r($task_files);
                                                       // Array ( [0] => computing_1 [1] => output [2] => test.mp3 [3] => uploadData.txt )

                                                       $computing_check='';
                                                       $computing_node = '';
                                                       if (in_array('computing_1', $task_files)) 
                                                       {
                                                            $computing_node = 'computing_1';
                                                            $computing_check = $task.'/computing_1';
                                                       }
                                                       if (in_array('computing_2', $task_files)) 
                                                       {
                                                            $computing_node = 'computing_2';
                                                            $computing_check = $task.'/computing_2';
                                                       }
                                                       if (in_array('computing_3', $task_files)) 
                                                       {
                                                            $computing_node = 'computing_3';
                                                            $computing_check = $task.'/computing_3';
                                                       }

                                                       $temp = file($task.'uploadData.txt');
                                                       // print_r($temp);
                                                       // Array ( [0] => test.mp3 [1] => 2024-04-22 14:22:49 [2] => 1713766969.5 )
                                                       $file_name = $temp[0];
                                                       $file_time = $temp[1];

                                                       $output_dir = $task.'output';
                                                       // echo $output_dir;
                                                       // /share/furry/1713766969.5/output
                                                                 
                                                       if (is_dir($output_dir)) 
                                                       {
                                                            $music_dir = scandir($output_dir);
                                                            array_shift($music_dir);
                                                            array_shift($music_dir);
                                                            // print_r($music_dir);
                                                            // Array ( [0] => test )

                                                            $output_files = scandir($output_dir.'/'.$music_dir[0]);
                                                            array_shift($output_files);
                                                            array_shift($output_files);
                                                            // print_r($output_files);
                                                            // Array ( [0] => accompaniment.wav [1] => vocals.wav )


                                                            if (count($output_files) >= 2) 
                                                            {
                                                                 echo '<tr><td>'.$casenum.'</td>';
                                                                 echo '<td>'.$info[2].'</td>';
                                                                 echo '<td>'.$file_time.'</td>';
                                                                 echo '<td>'.$file_name.'</td>';
                                                                 echo '<td>Finished</td>';
                                                                 echo '<td></td>';
                                                                 echo '</tr>';
                                                            }
                                                            // else 
                                                            // {
                                                            //      echo '<tr><td>'.$casenum.'</td>';
                                                            //      echo '<td>'.$info[2].'</td>';
                                                            //      echo '<td>'.$file_time.'</td>';
                                                            //      echo '<td>'.$file_name.'</td>';
                                                            //      echo '<td>Computing</td>';
                                                            //      echo '<td>'.$computing_node.'</td>';
                                                            //      echo '</tr>';
                                                            // }
                                                       }
                                                       elseif($computing_check!='')
                                                       {
                                                            echo '<tr><td>'.$casenum.'</td>';
                                                            echo '<td>'.$info[2].'</td>';
                                                            echo '<td>'.$file_time.'</td>';
                                                            echo '<td>'.$file_name.'</td>';
                                                            echo '<td>Computing</td>';
                                                            echo '<td>'.$computing_node.'</td>';
                                                            echo '</tr>';
                                                       }
                                                       else 
                                                       {
                                                            echo '<tr><td>'.$casenum.'</td>';
                                                            echo '<td>'.$info[2].'</td>';
                                                            echo '<td>'.$file_time.'</td>';
                                                            echo '<td>'.$file_name.'</td>';
                                                            echo '<td>Queuing</td>';
                                                            echo '<td></td>';
                                                            echo '</tr>';
                                                       }
                                                       $casenum = $casenum + 1;
                                                  }
                                             }
                                        }
                                        
                                   ?>
                              </table>
                         </table>
                    </div>
               </td>
          </tr>
     </table>                                             
</div>
</body>
</html>