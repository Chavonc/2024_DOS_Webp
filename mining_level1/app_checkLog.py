import os
import sys
from all_function import read_file, check_user_exists

#輸入查看餘額者帳號
user= sys.argv[1]
transaction = []

#檢查使用者是否存在
if (check_user_exists(user)):
    try:
        #列出record目錄中所有檔案
        file_list = os.listdir("./record/")
        #查user交易資料，print位於read_file function中，這邊呼叫function即可
        read_file(file_list, user)
    except:#發生異常
        print("Error usage!")
else:#使用者不存在
    print("User does not exist.")