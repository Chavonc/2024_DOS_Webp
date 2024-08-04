import os
import sys
#套用的functions
from all_function import write_file, check_user_exists
#檢查是否存在record目錄
if not os.path.exists("./record/"):
    #不存在，創建該目錄
    os.makedirs("./record/")

user1 = sys.argv[1]#轉帳者的帳號
user2 = sys.argv[2]#被轉帳者的帳號
money = sys.argv[3]#交易金額
#列出record目錄中所有檔案
record_file_list = os.listdir("./record/")
#檢查兩個使用者是否存在
if (check_user_exists(user1) and check_user_exists(user2)):
    if user1 == user2:#不能自己轉給自己
        print("Input error!")
    try:#可能發生的錯誤
        print(user1, user2, money)#交易的兩個使用者名稱和金額
        #將交易資訊寫入檔案
        write_file(record_file_list, user1, user2, money)
    except:#發生異常
        print("Error usage!")
else:#使用者不存在
    print("Please check your user name!")
    