<!-- webServer -->

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
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
        input[type=text],[type=password]
        {
            width:200px;
            height:40px;
            margin:10% auto;
        }
        table[id=logintable]
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
    <div id="logins">
        <div id="login_content">
            <table id="logintable">
                <tr><th><h2>雲端登入系統</h2></th></tr>                        
                <tr>
                    <th>
                        <form name="login" action="login_action.php" method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" name="name" placeholder="Username">
                            </div>
                            <p><p>
                            <div class="form-group">
                                <input class="form-control" type="password" name="password" placeholder="Password">
                            </div>
                            <p><p>
                            <div>
                                <a href="signup.php"><button type="button" class="btn btn-outline-light">註冊</button></a>
                                <button type="submit" name="submit" class="btn btn-outline-light">登入</button>
                            </div>
                        </form>
                    </th>
                </tr>
            </table>  
        </div>
    </div>
</body>
</html>