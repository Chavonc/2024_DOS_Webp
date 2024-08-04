<!-- webServer -->

<?php
ini_set('display_errors','1');// 設定 PHP 顯示所有錯誤訊息
error_reporting(E_ALL);
header('refresh: 60;');
if ($_COOKIE["name"] == "") 
{
     header("refresh:0;url=login.php");
     exit;
}
?>
<!doctype html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <title>查看資訊</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>    
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
     <script src="echarts.js"></script>
     <script src="vintage.js"></script>
     <meta http-equiv="refresh" content="60">
     <style>
          button[type=submit],[id=logout],[id=home],[id=info]
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
          button[id=us_Button],[id=id_Button],[id=idavg1_Button],[id=runqsz_Button]
          {
               border-radius: 50%;
               width:200px;
               height:35px;
               background-color:#6EC314;
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
          table[id=cpu]
          {
               width:100%;
               align:center;
               margin:auto;
               text-align:center;
               background-color:#E0FFFF;
          }
          table[id=content]
          {
               width:80%;
               align:center;
               margin:auto;
               text-align:center;
               background-color:#E0FFFF;
          }
          table[id=graph_action]
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
               height: 600px;
          }
          .relative2
          {
               position: absolute;
               top: 0%;
               left:-5%;
               width: 100%;
               height: 50px;
          }
          .CPU_relative
          {
               position: absolute;
               top: 7%;
               left:0%;
               width:90%;
               height: 30px;
          }
          .us_graph
          {
               position: absolute;
               top: 20%;
               left:10%;
               width:90%;
               height:450px;
          }
          .id_graph
          {
               position: absolute;
               top: 20%;
               left:10%;
               width:90%;
               height:450px;
          }
          .idavg1_graph
          {
               position: absolute;
               top: 20%;
               left:10%;
               width:90%;
               height:450px;
          }
          .runqsz_graph
          {
               position: absolute;
               top: 20%;
               left:10%;
               width:90%;
               height:450px;
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
                                        查看CPU累積資料                                  
                                   </td>
                                   <td style="text-align:right">
                                        <?php
                                             $user = $_COOKIE["name"];
                                        ?>
                                        <a href="queuingInfo.php">
                                             <button id="info" type="button" class="btn btn-outline-light">Info</button>
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
               </td>
          </tr>
          <tr>
               <td>
                    <div class="CPU_relative">
                         <table id="graph_action">
                              <tr>
                                   <td style="text-align:right">
                                        <button id="us_Button" type="button" class="btn btn-outline-light">進程消耗CPU百分比(%)</button>
                                   </td>
                                   <td style="text-align:right">
                                        <button id="id_Button" type="button" class="btn btn-outline-light">CPU空閒狀態百分比(%)</button>
                                   </td>
                                   <td style="text-align:left">
                                        <button id="idavg1_Button" type="button" class="btn btn-outline-light">前1分鐘的系統負載</button>
                                   </td>
                                   <td style="text-align:left">
                                        <button id="runqsz_Button" type="button" class="btn btn-outline-light">準備運行進程之隊列數</button>
                                   </td>                              
                              </tr>
                         </table>
                    </div>
               </td>
          </tr>
          <tr>
               <td>
                    <div class="us_graph" id="container1" style="width:700px;height:450px;"></div>
               </td>
               <td>
                    <div class="id_graph" id="container2" style="width:700px;height:450px;"></div>
               </td>
               <td>
                    <div class="idavg1_graph" id="container3" style="width:700px;height:450px;"></div>
               </td>
               <td>
                    <div class="runqsz_graph" id="container4" style="width:700px;height:450px;"></div>
               </td>
               <script type="text/javascript">
                    var usChart = null;
                    var idChart = null;
                    var idavg1Chart = null;
                    var runqszChart = null;
                    //預設顯示
                    document.addEventListener('DOMContentLoaded', function() 
                    {
                         document.getElementById('us_Button').click();
                    });
                    //點擊"us_Button"按鈕
                    document.getElementById('us_Button').addEventListener('click', function() 
                    {
                         //隱藏
                         document.getElementById('container2').style.display = 'none';
                         document.getElementById('container3').style.display = 'none';
                         document.getElementById('container4').style.display = 'none';
                         //顯示
                         document.getElementById('container1').style.display = 'block';
                         //改變按鈕背景顏色
                         document.getElementById('us_Button').style.backgroundColor = '#9B9E97';
                         document.getElementById('id_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('idavg1_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('runqsz_Button').style.backgroundColor = '#6EC314';

                         //初始化圖表
                         if (usChart === null) 
                         {
                              var chartDom1 = document.getElementById('container1');
                              usChart = echarts.init(chartDom1);
                              var us_data1=                                        
                                   <?php
                                        $cpu_info1 = fopen('/share/cpuinfo/info1.txt', 'r');
                                        $allCpuUs1 = [];$allCpuSy1 = [];$allCpuId1 = [];$allCpuWa1 = [];
                                        while ($line = fgets($cpu_info1)) 
                                        {
                                             // 抓有%Cpu的行
                                             if (strpos($line, '%Cpu') !== false) 
                                             {
                                                  $words = explode(' ', $line); // 用空格整理分開
                                                  // print_r($words);
                                                  $usIndex = array_search('us,', $words); // 找有us,的那格index
                                                  $syIndex = array_search('sy,', $words); // 找有sy,的那格index
                                                  $idIndex = array_search('id,', $words); // 找有id,的那格index
                                                  $waIndex = array_search('wa,', $words); // 找有wa,的那格index
                                                  if ($usIndex !== false && $usIndex > 0) 
                                                  {
                                                       // 獲取'us,'前面的值存入
                                                       if (strpos($words[$usIndex - 1], '100')) 
                                                       {
                                                           $word = explode(',', $words[$usIndex - 1]); // 用逗號整理分開
                                                           $words[$usIndex - 1] = $word[1];
                                                       }
                                                       $allCpuUs1[] = $words[$usIndex - 1];
                                                  }
                                                  if ($syIndex !== false && $syIndex > 0) 
                                                  {
                                                       // 獲取'sy,'前面的值存入
                                                       $allCpuSy1[] = $words[$syIndex - 1];
                                                  }
                                                  // id容易有100，要處理空格
                                                  if ($idIndex !== false && $idIndex > 0) 
                                                  {
                                                       // 獲取'id,'前面的值存入
                                                       if (strpos($words[$idIndex - 1], '100')) 
                                                       {
                                                            $word = explode(',', $words[$idIndex - 1]); // 用逗號整理分開
                                                            $words[$idIndex - 1] = $word[1];
                                                       }
                                                       $allCpuId1[] = $words[$idIndex - 1];
                                                  }
                                                  if ($waIndex !== false && $waIndex > 0) 
                                                  {
                                                       // 獲取'wa,'前面的值存入
                                                       $allCpuWa1[] = $words[$waIndex - 1];
                                                  }
                                             }
                                        }
                                        fclose($cpu_info1);
                                        echo json_encode($allCpuUs1);
                                   ?>;
                              var us_data2 = 
                                   <?php
                                        $cpu_info2 = fopen('/share/cpuinfo/info2.txt', 'r');
                                        $allCpuUs2 = [];$allCpuSy2 = [];$allCpuId2 = [];$allCpuWa2 = [];
                                        while ($line = fgets($cpu_info2)) 
                                        {
                                             // 抓有%Cpu的行
                                             if (strpos($line, '%Cpu') !== false) 
                                             {
                                                  $words = explode(' ', $line); // 用空格整理分開
                                                  // print_r($words);
                                                  $usIndex = array_search('us,', $words); // 找有us,的那格index
                                                  $syIndex = array_search('sy,', $words); // 找有sy,的那格index
                                                  $idIndex = array_search('id,', $words); // 找有id,的那格index
                                                  $waIndex = array_search('wa,', $words); // 找有wa,的那格index
                                                  if ($usIndex !== false && $usIndex > 0) 
                                                  {
                                                       // 獲取'us,'前面的值存入
                                                       if (strpos($words[$usIndex - 1], '100')) 
                                                       {
                                                           $word = explode(',', $words[$usIndex - 1]); // 用逗號整理分開
                                                           $words[$usIndex - 1] = $word[1];
                                                       }
                                                       $allCpuUs2[] = $words[$usIndex - 1];
                                                  }
                                                  if ($syIndex !== false && $syIndex > 0) 
                                                  {
                                                       // 獲取'sy,'前面的值存入
                                                       $allCpuSy2[] = $words[$syIndex - 1];
                                                  }
                                                  // id容易有100，要處理空格
                                                  if ($idIndex !== false && $idIndex > 0) 
                                                  {
                                                       // 獲取'id,'前面的值存入
                                                       if (strpos($words[$idIndex - 1], '100')) 
                                                       {
                                                            $word = explode(',', $words[$idIndex - 1]); // 用逗號整理分開
                                                            $words[$idIndex - 1] = $word[1];
                                                       }
                                                       $allCpuId2[] = $words[$idIndex - 1];
                                                  }
                                                  if ($waIndex !== false && $waIndex > 0) 
                                                  {
                                                       // 獲取'wa,'前面的值存入
                                                       $allCpuWa2[] = $words[$waIndex - 1];
                                                  }
                                             }
                                        }
                                        fclose($cpu_info2);
                                        echo json_encode($allCpuUs2);
                                   ?>;
                              var us_data3 = 
                                   <?php
                                        $cpu_info3 = fopen('/share/cpuinfo/info3.txt', 'r');
                                        $allCpuUs3 = [];$allCpuSy3 = [];$allCpuId3 = [];$allCpuWa3 = [];
                                        while ($line = fgets($cpu_info3)) 
                                        {
                                             // 抓有%Cpu的行
                                             if (strpos($line, '%Cpu') !== false) 
                                             {
                                                  $words = explode(' ', $line); // 用空格整理分開
                                                  // print_r($words);
                                                  $usIndex = array_search('us,', $words); // 找有us,的那格index
                                                  $syIndex = array_search('sy,', $words); // 找有sy,的那格index
                                                  $idIndex = array_search('id,', $words); // 找有id,的那格index
                                                  $waIndex = array_search('wa,', $words); // 找有wa,的那格index
                                                  if ($usIndex !== false && $usIndex > 0) 
                                                  {
                                                       // 獲取'us,'前面的值存入
                                                       if (strpos($words[$usIndex - 1], '100')) 
                                                       {
                                                           $word = explode(',', $words[$usIndex - 1]); // 用逗號整理分開
                                                           $words[$usIndex - 1] = $word[1];
                                                       }
                                                       $allCpuUs3[] = $words[$usIndex - 1];
                                                  }
                                                  if ($syIndex !== false && $syIndex > 0) 
                                                  {
                                                       // 獲取'sy,'前面的值存入
                                                       $allCpuSy3[] = $words[$syIndex - 1];
                                                  }
                                                  // id容易有100，要處理空格
                                                  if ($idIndex !== false && $idIndex > 0) 
                                                  {
                                                       // 獲取'id,'前面的值存入
                                                       if (strpos($words[$idIndex - 1], '100')) 
                                                       {
                                                            $word = explode(',', $words[$idIndex - 1]); // 用逗號整理分開
                                                            $words[$idIndex - 1] = $word[1];
                                                       }
                                                       $allCpuId3[] = $words[$idIndex - 1];
                                                  }
                                                  if ($waIndex !== false && $waIndex > 0) 
                                                  {
                                                       // 獲取'wa,'前面的值存入
                                                       $allCpuWa3[] = $words[$waIndex - 1];
                                                  }
                                             }
                                        }
                                        fclose($cpu_info3);
                                        echo json_encode($allCpuUs3);
                                   ?>;
                              var option = 
                              {
                                   title: 
                                   {
                                        text: ''//用戶進程消耗CPU百分比(us)
                                   },
                                   tooltip: 
                                   {
                                        trigger: 'axis'
                                   },
                                   legend: 
                                   {
                                        data:['ComputingNode1', 'ComputingNode2', 'ComputingNode3']
                                   },
                                   grid: 
                                   {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                   },
                                   toolbox: 
                                   {
                                        feature: 
                                        {
                                             saveAsImage: {}
                                        }
                                   },
                                   xAxis: 
                                   {
                                        type: 'category',
                                        boundaryGap: false,
                                        data: ['第1筆', '第2筆', '第3筆', '第4筆', '第5筆', '第6筆', '第7筆','第8筆','第9筆','第10筆']
                                   },
                                   yAxis: 
                                   {
                                        type: 'value',
                                        min: 0,
                                        max: 100
                                   },
                                   series: 
                                   [
                                        {
                                             name: 'ComputingNode1',
                                             type: 'line',
                                             smooth:true,
                                             //stack: 'Total',
                                             data: us_data1
                                        },
                                        {
                                             name: 'ComputingNode2',
                                             type: 'line',
                                             smooth:true,
                                             //stack: 'Total',
                                             data: us_data2
                                        },
                                        {
                                             name: 'ComputingNode3',
                                             type: 'line',
                                             smooth:true,
                                             //stack: 'Total',
                                             data: us_data3
                                        }
                                   ]
                              };
                              option && usChart.setOption(option);
                         }
                    });

                    //點擊"id_Button"按鈕
                    document.getElementById('id_Button').addEventListener('click', function() 
                    {
                         //隱藏
                         document.getElementById('container1').style.display = 'none';
                         document.getElementById('container3').style.display = 'none';
                         document.getElementById('container4').style.display = 'none';
                         //顯示
                         document.getElementById('container2').style.display = 'block';
                         //改變按鈕背景顏色
                         document.getElementById('id_Button').style.backgroundColor = '#9B9E97';
                         document.getElementById('us_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('idavg1_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('runqsz_Button').style.backgroundColor = '#6EC314';

                         //初始化圖表
                         if (idChart === null) 
                         {
                              var chartDom2 = document.getElementById('container2');
                              idChart = echarts.init(chartDom2);
                              var id_data1=<?php echo json_encode($allCpuId1);?>;
                              var id_data2=<?php echo json_encode($allCpuId2);?>;
                              var id_data3=<?php echo json_encode($allCpuId3);?>;
                              var option = 
                              {
                                   title: 
                                   {
                                        text: ''//CPU空閒百分比(id)
                                   },
                                   tooltip: 
                                   {
                                        trigger: 'axis',
                                        axisPointer: 
                                        {
                                             type: 'shadow'
                                        }
                                   },
                                   legend: {},
                                   grid: 
                                   {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                   },
                                   xAxis: 
                                   [
                                        {
                                             type: 'category',
                                             data: ['第1筆', '第2筆', '第3筆', '第4筆', '第5筆', '第6筆', '第7筆','第8筆','第9筆','第10筆']
                                        }
                                   ],
                                   yAxis: 
                                   [
                                        {
                                             type: 'value',
                                             min: 0,
                                             max: 100
                                        }
                                   ],
                                   series: 
                                   [
                                        {
                                             name: 'ComputingNode1',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: id_data1
                                        },
                                        {
                                             name: 'ComputingNode2',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: id_data2
                                        },
                                        {
                                             name: 'ComputingNode3',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: id_data3
                                        }
                                   ]
                              };
                              option && idChart.setOption(option);
                         }
                    });
                    //點擊"idavg1_Button"按鈕
                    document.getElementById('idavg1_Button').addEventListener('click', function() 
                    {
                         //隱藏
                         document.getElementById('container1').style.display = 'none';
                         document.getElementById('container2').style.display = 'none';
                         document.getElementById('container4').style.display = 'none';
                         //顯示
                         document.getElementById('container3').style.display = 'block';
                         //改變按鈕背景顏色
                         document.getElementById('idavg1_Button').style.backgroundColor = '#9B9E97';
                         document.getElementById('us_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('id_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('runqsz_Button').style.backgroundColor = '#6EC314';

                         //初始化圖表
                         if (idavg1Chart === null) 
                         {
                              var chartDom3 = document.getElementById('container3');
                              idavg1Chart = echarts.init(chartDom3,'vintage');
                              var idavg1_data1=
                              <?php 
                                   $cpu_info1 = fopen('/share/loadinfo/info1.txt', 'r');
                                   $runqsz_1 = [];$plistsz_1 = [];$ldavg1_1 = [];$ldavg5_1 = [];
                                   while ($line = fgets($cpu_info1)) 
                                   {
                                        // 抓沒有Linux、runq-sz、Average的非空行
                                        if (trim($line) !== '' && strpos($line, 'Linux') === false && strpos($line, 'runq-sz') === false && strpos($line, 'Average') === false) 
                                        {
                                             $words = explode(' ', $line); // 用空格整理分開
                                             // print_r($words);
                                             if ($words[12] == "") 
                                             {
                                                  $runqsz_1[] = $words[11];
                                                  $plistsz_1[] = $words[18];
                                                  $ldavg1_1[] = $words[24];
                                                  $ldavg5_1[] = $words[30];
                                             } 
                                             else 
                                             {
                                                  $runqsz_1[] = $words[12];
                                                  $plistsz_1[] = $words[19];
                                                  $ldavg1_1[] = $words[25];
                                                  $ldavg5_1[] = $words[31];
                                             }
                                        }
                                   }
                                   fclose($cpu_info1);
                                   echo json_encode($ldavg1_1);
                                   
                              ?>;
                              var idavg1_data2=
                              <?php 
                                   $cpu_info2 = fopen('/share/loadinfo/info2.txt', 'r');
                                   $runqsz_2 = [];$plistsz_2 = [];$ldavg1_2 = [];$ldavg5_2 = [];
                                   while ($line = fgets($cpu_info2)) 
                                   {
                                        // 抓沒有Linux、runq-sz、Average的非空行
                                        if (trim($line) !== '' && strpos($line, 'Linux') === false && strpos($line, 'runq-sz') === false && strpos($line, 'Average') === false) 
                                        {
                                             $words = explode(' ', $line); // 用空格整理分開
                                             // print_r($words);
                                             if ($words[12] == "") 
                                             {
                                                  $runqsz_2[] = $words[11];
                                                  $plistsz_2[] = $words[18];
                                                  $ldavg1_2[] = $words[24];
                                                  $ldavg5_2[] = $words[30];
                                             } 
                                             else 
                                             {
                                                  $runqsz_2[] = $words[12];
                                                  $plistsz_2[] = $words[19];
                                                  $ldavg1_2[] = $words[25];
                                                  $ldavg5_2[] = $words[31];
                                             }
                                        }
                                   }
                                   fclose($cpu_info2);
                                   echo json_encode($ldavg1_2);
                              ?>;
                              var idavg1_data3=
                              <?php 
                                   $cpu_info3 = fopen('/share/loadinfo/info3.txt', 'r');
                                   $runqsz_3 = [];$plistsz_3 = [];$ldavg1_3 = [];$ldavg5_3 = [];
                                   while ($line = fgets($cpu_info3)) 
                                   {
                                        // 抓沒有Linux、runq-sz、Average的非空行
                                        if (trim($line) !== '' && strpos($line, 'Linux') === false && strpos($line, 'runq-sz') === false && strpos($line, 'Average') === false) 
                                        {
                                             $words = explode(' ', $line); // 用空格整理分開
                                             // print_r($words);
                                             if ($words[12] == "") 
                                             {
                                                  $runqsz_3[] = $words[11];
                                                  $plistsz_3[] = $words[18];
                                                  $ldavg1_3[] = $words[24];
                                                  $ldavg5_3[] = $words[30];
                                             } 
                                             else 
                                             {
                                                  $runqsz_3[] = $words[12];
                                                  $plistsz_3[] = $words[19];
                                                  $ldavg1_3[] = $words[25];
                                                  $ldavg5_3[] = $words[31];
                                             }
                                        }
                                   }
                                   fclose($cpu_info3);
                                   echo json_encode($ldavg1_3);
                              ?>;
                              var option = 
                              {
                                   title: 
                                   {
                                        text: ''//前1分鐘的系統平均負載(ldavg-1)
                                   },
                                   tooltip: 
                                   {
                                        trigger: 'axis',
                                        axisPointer: 
                                        {
                                             type: 'shadow'
                                        }
                                   },
                                   legend: {},
                                   grid: 
                                   {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                   },
                                   xAxis: 
                                   [
                                        {
                                             type: 'category',
                                             data: ['第1筆', '第2筆', '第3筆', '第4筆', '第5筆', '第6筆', '第7筆','第8筆','第9筆','第10筆']
                                        }
                                   ],
                                   yAxis: 
                                   [
                                        {
                                             type: 'value',
                                             min: 0.00,
                                             max: 20.00
                                        }
                                   ],
                                   series: 
                                   [
                                        {
                                             name: 'ComputingNode1',
                                             type: 'line',
                                             smooth: true,
                                             data: idavg1_data1
                                        },
                                        {
                                             name: 'ComputingNode2',
                                             type: 'line',
                                             smooth: true,                                             
                                             data: idavg1_data2
                                        },
                                        {
                                             name: 'ComputingNode3',
                                             type: 'line',
                                             smooth: true,
                                             data: idavg1_data3
                                        }
                                   ]
                              };
                              option && idavg1Chart.setOption(option);
                         }
                    });
                    //點擊"runqsz_Button"按鈕
                    document.getElementById('runqsz_Button').addEventListener('click', function() 
                    {
                         //隱藏
                         document.getElementById('container1').style.display = 'none';
                         document.getElementById('container2').style.display = 'none';
                         document.getElementById('container3').style.display = 'none';
                         //顯示
                         document.getElementById('container4').style.display = 'block';
                         // 改變按鈕背景顏色
                         document.getElementById('runqsz_Button').style.backgroundColor = '#9B9E97';
                         document.getElementById('us_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('id_Button').style.backgroundColor = '#6EC314';
                         document.getElementById('idavg1_Button').style.backgroundColor = '#6EC314';

                         //初始化圖表
                         if (runqszChart === null) 
                         {
                              var chartDom4 = document.getElementById('container4');
                              runqszChart = echarts.init(chartDom4,'vintage');
                              var runqsz_data1=<?php echo json_encode($runqsz_1);?>;
                              var runqsz_data2=<?php echo json_encode($runqsz_2);?>;
                              var runqsz_data3=<?php echo json_encode($runqsz_3);?>;
                              var option = 
                              {
                                   title: 
                                   {
                                        text: ''//準備運行進程之隊列數(runq-sz)
                                   },
                                   tooltip: 
                                   {
                                        trigger: 'axis',
                                        axisPointer: 
                                        {
                                             type: 'shadow'
                                        }
                                   },
                                   legend: {},
                                   grid: 
                                   {
                                        left: '3%',
                                        right: '4%',
                                        bottom: '3%',
                                        containLabel: true
                                   },
                                   xAxis: 
                                   [
                                        {
                                             type: 'category',
                                             data: ['第1筆', '第2筆', '第3筆', '第4筆', '第5筆', '第6筆', '第7筆','第8筆','第9筆','第10筆']
                                        }
                                   ],
                                   yAxis: 
                                   [
                                        {
                                             type: 'value',
                                             min: 0,
                                             max: 100
                                        }
                                   ],
                                   series: 
                                   [
                                        {
                                             name: 'ComputingNode1',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: runqsz_data1
                                        },
                                        {
                                             name: 'ComputingNode2',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: runqsz_data2
                                        },
                                        {
                                             name: 'ComputingNode3',
                                             type: 'bar',
                                             emphasis: 
                                             {
                                                  focus: 'series'
                                             },
                                             data: runqsz_data3
                                        }
                                   ]
                              };
                              option && runqszChart.setOption(option);
                         }
                    });
               </script>          
          </tr>
     </table>                                             
</div>
</body>
</html>