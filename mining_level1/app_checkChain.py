import os
import sys
from all_function import write_file, check_chain,check_user_exists

#檢查是否存在record目錄
if not os.path.exists("./record/"):
    os.makedirs("./record/")#不存在，創建該目錄
    
#輸入被轉帳者帳號
to_user= sys.argv[1]

#檢查使用者是否存在
if (check_user_exists(to_user)):
     #print(to_user+" exist.")
     try:
          #列出record目錄中所有檔案
          record_file_list = os.listdir("./record/")
          # 取得錯誤檔案名或True
          quest_filename = check_chain(record_file_list)  
          #print("quest_filename:"+ str(quest_filename))
          #所有記錄都正確
          if quest_filename==True:  
               print("OK.")#帳本鍊完整，在螢幕顯示OK
               #檢查完成可從angel得到10元獎勵
               if(to_user=="angel"):
                    print("You could not receive 10 bonus from yourself!")
               else:
                    write_file(record_file_list,"angel", to_user, 10)
                    print("Congratulation! "+ to_user +" have Received 10 bonus from angel!")
     #存在錯誤的記錄
          else: 
               #帳本鍊受損，在螢幕顯示有錯誤的區塊編號
               print("Recorded the erroneous hash value: "+ quest_filename +" error.")

     except:#輸入錯誤
          print("Input Error. Please check your user name!")  
else:#使用者不存在
    print("User does not exist.")
    
