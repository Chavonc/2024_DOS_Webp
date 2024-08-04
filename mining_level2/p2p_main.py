import socket
import threading
import os
from all_function import check_chain, write_file, read_file, check_all_user_money, create_hash_string, find_max_count_and_element, check_error_hash, read_txt_file, rewrite_txt_file
import re
from collections import Counter
from ast import literal_eval

class P2PNode:
    def __init__(self, selfIP, peers):
        self.port = selfIP[1]
        self.ip = selfIP[0]
        self.peers = peers
        self.sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        self.sock.bind((self.ip, self.port))

    def start(self):
        threading.Thread(target=self._listen).start()
        threading.Thread(target=self._send_messages(None)).start()

    def _listen(self):
        while True:
            data, addr = self.sock.recvfrom(100000)
            print(f"Received {data.decode('utf-8')} from {addr}")
            self.command_exe(data.decode('utf-8'), False, addr)

    def _send_messages(self, s_mesg):
        while True:
            s_mesg = self.command_exe(s_mesg, True, None)
            if s_mesg:
                for peer in self.peers:
                    self.sock.sendto(s_mesg.encode('utf-8'), peer)
                s_mesg = None
            else:
                s_mesg = input("Enter a command (checkMoney, checkLog, transaction, checkChain, checkAllChains, make_consensus): ")


    def command_exe(self, command, is_sender, s_addr):
        total_peer = [('172.17.0.2', 8001), ('172.17.0.3', 8001), ('172.17.0.4', 8001)]
        if command :
            file_list = os.listdir("./record/")
            file_list.sort(key=lambda x: int(re.sub('\D', '', x)))
            commands = command.split()

            # 轉帳
            if commands[0] == "transaction":
                write_file(file_list, commands[1], commands[2], int(commands[3]))
                return command  

            # 確認使用者金額
            elif commands[0] == "checkMoney":
                money = check_all_user_money(commands[1], file_list, 1, 1)
                print(money)

            # 確認使用者交易紀錄
            elif commands[0] == "checkLog":
                record_list = read_file(file_list, commands[1])
                for index, record in enumerate(record_list):
                    print(f"{index+1}: {record[0]}, {record[1]}, {record[2]}")

            # 確認單一container的帳本鍊完整性
            elif commands[0] == 'checkChain':
                filename = check_chain(file_list)
                if filename == True:
                    print("all ledgers are Correct!!!")
                    write_file(file_list, "angel", commands[1], 10)
                    return f"transaction angel {commands[1]} 10"
                else:
                    print(f"ledger data error: {filename} error")

            # 呼叫全部的container檢查所有的帳本鍊
            elif commands[0] == 'checkAllChains':
                if is_sender:
                    # sender
                    global result_list
                    global result_single
                    result_list = []
                    result_single = []
                    return command
                else:
                    # receiver
                    r_sha = ("./record/" + file_list[-1])
                    read_hash = create_hash_string(r_sha)
                    new_msg = f"checkAllChains_step1 {self.ip} {read_hash} {commands[1]}"
                    self.sock.sendto(new_msg.encode('utf-8'), s_addr)

            # 第一步：先檢查每一個container的最後一個txt檔案是否相同
            elif commands[0] == 'checkAllChains_step1':
                # get receiver's sha
                my_sha = "./record/" + file_list[-1]
                my_read_hash = create_hash_string(my_sha)
                # 若不相同
                if str(my_read_hash) != str(commands[2]):
                    print(f"\n------- Inconsistent {commands[1]} -------")
                    result_list.append("No")
                    print("compare each node's last file: No")
                # 若相同
                else:
                    print(f"\n------- Consistent {commands[1]} --------")
                    result_list.append("Yes")
                    print("compare each node's last file: Yes")
                # 準備執行第二步
                if len(result_list) > (len(peers) - 1):
                    ans = "Yes"
                    for i in range(len(result_list)):
                        if result_list[i] == "No":
                            ans = "No"
                    new_msg = f"checkAllChains_step2 {commands[3]} {ans}"
                    for peer in self.peers:
                        self.sock.sendto(new_msg.encode('utf-8'), peer)
            
            # 第二步：除了sender以外的container檢查自己的帳本鍊完整性
            elif commands[0] == 'checkAllChains_step2':
                filename = check_chain(file_list)
                ans = ""
                # 若自己的帳本鍊完整，且最後一個txt檔案跟sender相同
                if filename == True and commands[2] == "Yes":
                    ans = "Yes"
                # 若前一步有錯，或是這一步有錯，或是兩步都錯
                else:
                    ans = "No"
                # 準備執行第三步
                new_msg = f"checkAllChains_step3 {self.ip} {ans} {commands[1]}"
                self.sock.sendto(new_msg.encode('utf-8'), s_addr)
            
            # 第三步：sender比較確定自己的帳本鍊完整性，跟其他container丟回來的答案比較
            elif commands[0] == 'checkAllChains_step3':
                # sender確認自己的帳本鍊完整性
                filename = check_chain(file_list)
                # sender將收到的答案跟自己的進行比較，若sender的是對的且其他人的答案也是對的
                if commands[2] == "Yes" and filename == True:
                    print("each container's block chain seperately: ", commands[1], " ", commands[2])
                    result_single.append(commands[2])
                # 若有任一個的答案是No
                else:
                    print(commands[1], " ", commands[2])
                # 大家帳本鍊都是正確的，檢查者得到100元
                if len(result_single) == len(peers):
                    print("all ledgers are Correct!!!")
                    write_file(file_list, "angel", commands[3], 100)
                    new_msg = f"transaction angel {commands[3]} 100"
                    for peer in self.peers:
                        self.sock.sendto(new_msg.encode('utf-8'), peer)
                else:
                    print(f"others ledger data error.")
            
            elif commands[0] == "make_consensus":
                global hash_list
                hash_list = []
                my_ip = self.ip
                check_num = int(commands[1])
                new_msg = f"make_consensus_step1 {check_num} {my_ip}"
                for peer in total_peer:
                    self.sock.sendto(new_msg.encode(), peer)

            # container們把txt的sha值傳給sender
            elif commands[0] == "make_consensus_step1":
                check_num =  int(commands[1])
                current_num = int(commands[1])
                ori_sender = commands[2]
                print(f"Check file: {file_list[check_num]}")
                current_file = f'./record/' + file_list[current_num]
                r_sha = create_hash_string(current_file)
                new_msg = f"make_consensus_step2 {self.ip} {r_sha} {current_num} {check_num} {ori_sender}"
                self.sock.sendto(new_msg.encode('utf-8'), s_addr)
            
            # sender接收所有的sha值
            elif commands[0] == "make_consensus_step2":
                other_sha = commands[2]
                current_num = int(commands[3])
                check_num = int(commands[4])
                hash_list.append([str(s_addr), str(other_sha)])
                ori_sender = commands[5]

                # 全部都接收到了
                if len(hash_list) == len(total_peer):
                    print("All received.")
                    # 數每一個sha的個數，並回傳最大值
                    max_count, element = find_max_count_and_element(hash_list)

                    #  如果有最大值
                    if element != None:
                        # 比對誰的hash是錯誤的
                        error_container = check_error_hash(element[1], hash_list)
                    else:   # 每個人的hash都不一樣，無法比較
                        error_container = False

                    # 大家都是正確的hash
                    if error_container == None:
                        check_num = check_num + 1
                        print(f"{file_list[current_num]} are all correct")
                        ori_sender = f"(\'{ori_sender}\', 8001)"
                        if check_num <  len(file_list):   
                            new_msg = f"make_consensus {check_num}"
                            for peer in total_peer:
                                if ori_sender == str(peer):
                                    self.sock.sendto(new_msg.encode('utf-8'), peer)
                        else:
                            print("Finish.")
                    # 這個系統不被信任
                    elif error_container == False:
                        print("System can NOT be trusted.")
                    # 只有其中一個人的hash有錯
                    else:
                        print(f"{file_list[current_num]} need to be revised. \n")
                        # 呼叫取得正確的txt資料
                        error_container = str(error_container[2:12])
                        new_msg = f"make_consensus_step3 {self.ip} {current_num} {error_container} {check_num}"
                        for peer in total_peer:
                            if element[0] == str(peer): # 擁有正確txt檔案的container
                                self.sock.sendto(new_msg.encode('utf-8'), peer)
            
            # 取得正確的txt資料
            elif commands[0] == "make_consensus_step3":
                ori_sender = commands[1]
                current_num = int(commands[2])
                my_file = f'./record/' + file_list[current_num]
                error_container = commands[3]
                check_num = int(commands[4])
                currect_txt_lines = read_txt_file(my_file)
                new_msg = f"make_consensus_step4 {self.ip} {currect_txt_lines} {current_num} {ori_sender} {check_num}"
                error_container = f"(\'{error_container}\', 8001)"
                # 把資料傳給錯誤的container
                for peer in peers:
                    if error_container == str(peer):
                        self.sock.sendto(new_msg.encode('utf-8'), peer)

            # 錯誤的container修正資料
            elif commands[0] == "make_consensus_step4":
                rewrite_content = commands[2]
                current_num = int(commands[3])
                ori_sender = commands[4]
                check_num = int(commands[5])
                rewrite_file = f'./record/{file_list[current_num]}'

                rewrite_txt_file(rewrite_file, rewrite_content)
                
                new_msg = f"make_consensus {check_num}"
                ori_sender = f"(\'{ori_sender}\', 8001)"
                for peer in total_peer:
                    if  ori_sender == str(peer):
                        self.sock.sendto(new_msg.encode('utf-8'), peer)
        return None
    


if __name__ == '__main__':
    ip = socket.gethostbyname(socket.gethostname())
    selfIP = (ip, 8001)
    peers = [('172.17.0.2', 8001), ('172.17.0.3', 8001), ('172.17.0.4', 8001)]
    peers = [peer for peer in peers if selfIP != peer]
    node = P2PNode(selfIP, peers)
    node.start()