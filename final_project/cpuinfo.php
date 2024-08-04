<!-- computingNode1, 2, 3, 4, 5, 6 -->

<?php
//設定 PHP 顯示所有錯誤訊息
ini_set('display_errors','1');
error_reporting(E_ALL);
// > 是把輸出轉向到指定的文件，如文件已存在話也会重新寫入，文件原内容不會保留
// >> 是把輸出附向到文件的後面，文件原内容會保留下来
// $cmd = "date -R > /share/cpuinfo/info1.txt; top -bn 1 -i -c >> /share/cpuinfo/info1.txt";
// shell_exec($cmd);//執行 shell 命令

// 定義最大紀錄次數
$max_required_times = 10;
// 檢查文件是否存在，若不存在則幫忙創建文件(要修改程式碼噢)
// $file_path = '/share/cpuinfo/info1.txt';
// $file_path = '/share/cpuinfo/info2.txt';
$file_path = '/share/cpuinfo/info3.txt';
if (!file_exists($file_path)) 
{
    touch($file_path); // 創建文件
}
// 將新的資料輸出到檔案末尾
$cmd = "date -R >> {$file_path}; top -bn 1 -i -c >> {$file_path}";
shell_exec($cmd);
// 讀取文件內容，按行儲存入$file_lines
$file_lines = file($file_path, FILE_IGNORE_NEW_LINES);
// 查找第一組包含 "+0800" 格式的紀錄行，並標記開始刪除的位置
$start_index = -1;
$delete_mode = false;
// 找包含+0800的行有幾行
$count = 0;
foreach ($file_lines as $line) 
{
    if (strpos($line, '+0800') !== false) 
     {
          $count++;
     }
}

if ($count > $max_required_times) 
{
    foreach ($file_lines as $index => $line) 
     {
          // 字串中若包含+0800
          if (strpos($line, '+0800') !== false) 
          {
               // 若$delete_mode == true，則表示這不是刪除中的第一個包含'+0800'的行，不能刪，要break掉
               if ($delete_mode) 
               {
                    // 找到下一個包含 "+0800" 的行，停止刪除模式
                    break;
               } 
               else 
               {
                    // 找到第一個包含 "+0800" 的行，開始刪除模式
                    $start_index = $index; // 開始刪除的行數
                    $delete_mode = true;
               }
          }

          // 如果現在處於刪除模式，刪除當前行
          if ($delete_mode) 
          {
               unset($file_lines[$index]);
          }
     }
}
// 重新寫入文件
file_put_contents($file_path, implode("\n", $file_lines) . "\n");
?>
