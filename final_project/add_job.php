<!-- webServer -->

<?php
    $user = $_GET['user_id'];
    $job_url_id = $_GET['job_url_id'];
    setcookie("name", $user, time()+3600, "/");

    $dirPath = "/share/";
    $userPath = $dirPath.$user;

    if (!is_dir($userPath)) {
        $command = "mkdir $userPath";
        shell_exec($command);
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>確認音樂</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        button[type=submit],[type=button]
        {
            border-radius: 10%;
            border:none;
            width:100px;
            height:35px;
            background-color:#6495ED;
            color:white;
        }
        input[type=text]
        {
            width:200px;
            height:40px;
            margin:10% auto;
        }
        table[id=checktable]
        {
            border-radius: 10%;
            border:none;
            width:100px;
            height:30px;
            background-color:white;
            width:30%;
            margin:10% auto;
            text-align:center;
        }
    </style>
</head>

<body style="background-color:#FFE4C4;">
    <div id="checks">
        <div id="check_content">
            <table id="checktable">
                <tr><th><h2>檢查音樂名稱</h2></th></tr>                        
                <tr>
                    <th>
                        <form id="add_job" name="add_job" action="add_job_exe.php" method="post">
                            <p style="color: darkorange;">請注意！名稱只能輸入英文與數字，不包含特殊符號與空格。</p>
                            <p style="color: darkorange;">不用輸入任何副檔名。</p>
                            <div class="form-group">
                                <input class="form-control" type="text" id="music_id" name="music_id" value= "<?php echo $job_url_id; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="music_name" name="music_name" placeholder="音樂名稱">
                            </div>
                            <p id="error-message" style="color: red;"></p>
                            <div>
                                <button type="submit" name="submit" class="btn btn-outline-light">送出</button>
                            </div>
                        </form>
                    </th>
                </tr>
            </table>  
        </div>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const form = document.getElementById('add_job');
        const inputElement = document.getElementById('music_name');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', (e) => {
            const value = inputElement.value;
            // 檢查是否只包含英文字符和數字
            if (!/^[a-zA-Z0-9]*$/.test(value)) {
                // 顯示錯誤訊息
                errorMessage.textContent = '請再次檢查您的名稱格式！';
                // 阻止表單提交
                e.preventDefault();
            } else {
                // 清除錯誤訊息
                errorMessage.textContent = '';
            }
        });

        inputElement.addEventListener('input', () => {
            const value = inputElement.value;
            // 檢查是否只包含英文字符
            if (/^[a-zA-Z0-9]*$/.test(value)) {
                // 清除錯誤訊息
                errorMessage.textContent = '';
            } else {
                // 顯示錯誤訊息
                errorMessage.textContent = '只能輸入英文或數字';
                // 移除非法字符
                // inputElement.value = value.replace(/[^a-zA-Z0-9]/g, '');
            }
        });
    });
</script>

</html>