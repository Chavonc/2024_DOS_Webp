import os
import sys
from all_function import check_all_user_money, check_user_exists

#輸入查看餘額者帳號
user= sys.argv[1]

#檢查使用者是否存在
if (check_user_exists(user)):
    try:
        #列出record目錄中所有檔案
        file_list = os.listdir("./record/")
        #查user餘額，參數money給1即可，反正用不到，參數ifCheckMoney給1，以示拿餘額
        money = check_all_user_money(user, file_list, 1, 1)
        print(money)
    except:#發生異常
        print("Error usage!")
else:#使用者不存在
    print("User does not exist.")