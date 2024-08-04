<!-- webServer -->

<?php
    header("Content-Type: text/html; charset=utf8");
    //顯示所有錯誤訊息
    ini_set("display_errors", "1");
    error_reporting(E_ALL);

    $filePath=$_GET['file'];
    //檢測是否存在 
    if(!file_exists($filePath))
    {
        echo('要求下載之檔案並不存在.');
        exit();
    }
    else
    {
        $fileName=explode('/',$filePath);
        // print_r($fileName);
        $final_name=$fileName[4];
        Header("Content-Type: "."audio/mpeg");
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