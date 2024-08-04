<!-- computingNode1, 2, 3, 4, 5, 6 -->

<?php
// 設定 PHP 顯示所有錯誤訊息
ini_set('display_errors', '1');
error_reporting(E_ALL);

// 定義最大紀錄次數
$max_required_times = 10;

// 檢查文件是否存在，若不存在則幫忙創建文件
// $file_path = '/share/loadinfo/info1.txt';
// $file_path = '/share/loadinfo/info2.txt';
$file_path = '/share/loadinfo/info3.txt';
if (!file_exists($file_path)) 
{
    touch($file_path); // 創建文件
}

// 將新的資料覆蓋掉舊檔案
$cmd = "sar -q > {$file_path}";
shell_exec($cmd);

// 讀取文件內容，按行儲存入$file_lines
$file_lines = file($file_path, FILE_IGNORE_NEW_LINES);


// 刪除第一行不包含Linux、runq-sz、Average的非空行
// 紀錄現在有幾筆數據
$count = 0;
foreach ($file_lines as $line) 
{
    if (trim($line) !== '' && strpos($line, 'Linux') === false && strpos($line, 'runq-sz') === false && strpos($line, 'Average') === false) 
    {
        $count++;
    }
}

foreach ($file_lines as $index => $line) 
{
    if (trim($line) !== '' && strpos($line, 'Linux') === false && strpos($line, 'runq-sz') === false && strpos($line, 'Average') === false) 
    {
        if ($count > $max_required_times) 
        {
            unset($file_lines[$index]);
            $count--;
        }
    }
}

// 重新寫入文件
file_put_contents($file_path, implode("\n", $file_lines) . "\n");
