import hashlib #用於計算hash值
import re #正則表達式
import os
    
#創建sha256的hash
def create_hash_string(file):
    sha256_data_block = hashlib.sha256()
    with open(file,"rb") as f:  # "rb" 模式表示以二進位模式讀取檔案
        #  每次從檔案中讀取4096個位元組(或4KB）的data block, 當data block為空字串則離開loop
        for byte_block in iter(lambda: f.read(4096), b""):   
            # 將每個data block更新到sha256_data_block
            sha256_data_block.update(byte_block) 
    # 獲取sha256_data_block的16進位表示
    return sha256_data_block.hexdigest()

#文件的設定
def check_file_state(file_list):
    # sort the file name, '\D'代表非數值的字元
    file_list.sort(key=lambda x: int(re.sub('\D', '', x))) # 將文件名稱轉為數值再排序
    file_num = len(file_list)   # 取得txt總數量
    last_file = f"./record/{file_num}.txt"  # 最後一個文件的名稱
    num_lines = sum(1 for line in open(last_file))  # txt裡面有多少行
    if num_lines > 6:   # 當總行數為7表示此block已經滿了
        with open(last_file, "r") as f: # 先到最後一個文件中取得下一個文件的名稱
            new_file = f.readlines()[1].split(":")[1]   # 獲取下一個文件的名稱
            new_file = "./record/" + re.sub("\n| ", "", new_file)# 去除多餘的空格和換行符
        
        # 利用最後一個文件的所有內容去計算sha256
        sha_code = create_hash_string(last_file)

        # 開啟新文件, 將sha256以及下一個文件名稱寫進去
        with open(new_file, 'w') as f:
            f.write(f"Sha256 of previous block: {sha_code}\n")# 將前一個區塊的 hash 值寫入新文件
            f.write(f"Next block: {file_num + 2}.txt\n")# 將下一個文件的名稱寫入新文件
        
        # 將新文件回傳
        return new_file
    else:   # 文件還可以繼續記錄
        return last_file

# 判斷user身上的錢是否足夠
# ifCheckMoney == 1: 回傳 user1 餘額(查詢餘額功能也用這個function)
def check_all_user_money(user1, file_list, money, ifCheckMoney):
    file_list.sort(key=lambda x: int(re.sub('\D', '', x)))

    angel_total = 10000000000
    user_sum = 0
    transaction = []
    for file_name in file_list:
        with open("./record/" + file_name, "r") as f:
            for i, line in enumerate(f):
                if i > 1:# 忽略前兩行（前一個區塊hash值和下一個區塊文件名稱）
                    line = re.sub("\n| ", "", line).split(",")
                    if user1 in line:
                        transaction.append(line)
                        
    for list in transaction:
        if user1 == list[0]:#是交易的發起者
            user_sum -= int(list[2])#減去交易金額
        else:
            user_sum += int(list[2])
    
    # 如果是angel，將user_sum的金額改為angel的總金額，不會影響到下面的回傳
    if user1 == 'angel':
        angel_total += user_sum
        user_sum = angel_total

    # 如果ifCheckMoney == 1，回傳餘額
    if ifCheckMoney == 1:
        return user_sum
    # 如果ifCheckMoney == 0，回傳true或false判斷錢夠不夠用
    else:
        if int(user_sum) >= int(money):#用戶的總金額大於等於交易金額
            return True
        else:
            return False
    
#比對每個文件所記錄的sha值(hash值)
def check_chain(record_file_list):
    #利用文件名的數字對txt進行檢查排序
    record_file_list.sort(key=lambda f: int(re.sub('\D', '', f)))  
    #排序後的txt
    for index, record_file_name in enumerate(record_file_list):  
        #忽略檢查1.txt的hash值
        if index > 0: 
            with open("./record/" + record_file_name, "r") as f:
                #讀取及提取所記錄前一個區塊的hash值
                read_hash = re.sub("\n| ", "", f.readline()).split(":")[1]
                read_hash = read_hash.replace(" ", "") #去除空格
                #計算前一個文件的hash值
                previous_hash = create_hash_string("./record/"+ record_file_list[index-1])  
                #比對失敗
                if previous_hash != read_hash:
                    #返回前一個文件的文件名(區塊編號)
                    return record_file_list[index-1]  
    #所有文件的hash值一致
    return True

#把交易資料寫入
def write_file(file_list, user1, user2, money):
    # file_list.sort(key=lambda x: int(re.sub('\D', '', x)))
    file = check_file_state(file_list)
    check_user_state = check_all_user_money(user1, file_list, money, 0)

    if check_user_state:
        try:
            with open(file, "a") as f:
                f.write(f"{user1}, {user2}, {money}\n")
            # return file
        except:
            print("File error.")
    else:
        print("Sorry, you do not enough money to transfer.")
        
#讀取user交易資料
def read_file(file_list, user):
    # file_list.sort(key=lambda x: int(re.sub('\D', '', x)))
    transaction = []
    
    for file_name in file_list:
        with open("./record/" + file_name, "r") as f:
            for i, line in enumerate(f):
                if i > 1:# 忽略前兩行（前一個區塊hash值和下一個區塊文件名稱）
                    line = re.sub("\n| ", "", line).split(",")
                    if user in line:
                        transaction.append(line)
    return transaction

def count_elements(arr):
        element_count = {}
        for element in arr:
            if element[1] not in element_count:
                element_count[element[1]] = [element[0], 1]
            else:
                element_count[element[1]][1] += 1
        return element_count

def find_max_count_and_element(arr):
    element_count = count_elements(arr)
    max_count = 1
    max_element = None
    for element, count_info in element_count.items():
        count = count_info[1]
        if count > max_count:
            max_count = count
            max_element = (count_info[0], element)
        elif count == max_count:
            max_element = None
    return max_count, max_element

def check_error_hash(true_hash, hash_list):
    error_container = None
    for i in range(len(hash_list)):
        if true_hash != hash_list[i][1]:
            error_container = hash_list[i][0]
    
    return error_container

def read_txt_file(file_name):
    context = ""
    
    with open(file_name,"r") as f:
        context = f.read()
    context = context.replace('\n', "#")
    context = context.replace(" ", "*")
    context = str(context)
    return context


def rewrite_txt_file(file_name,  content):
    content = content.replace("#", "\n")
    content = content.replace("*", " ")
    with open(file_name, "w") as f:
        f.write(content)
    f.close()
