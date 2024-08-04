# computingNode4, 5, 6

import ssl
import sys
ssl._create_default_https_context = ssl._create_stdlib_context

import os
from pytube import YouTube

from datetime import datetime

# 使用 sys.argv 來讀取命令行參數
# 第一個參數是腳本名稱本身，因此從索引 1 開始是傳遞給腳本的參數
if len(sys.argv) > 1:
    # 第一個命令行參數
    user_task_path = str(sys.argv[1])
    task_folder = user_task_path.split('/')[3]
    music_set_path = user_task_path + '/music_info.txt'

    # 打開文件以讀取模式 ('r')
    with open(music_set_path, 'r') as file:
        # 使用 readlines() 方法將文件的每一行讀取到一個列表中
        lines = file.readlines()

    # 移除每行末尾的換行符
    lines = [line.strip() for line in lines]
    # print(lines)

    http = 'https://www.youtube.com/watch?v=' + lines[0]
    music_name = lines[1] + '.mp3'

    music_dest = user_task_path + '/' + music_name

    yt = YouTube(http)
    yt.streams.filter().get_audio_only().download(filename=music_dest)

    # 獲取當前時間
    current_time = datetime.now()

    # 將時間格式化為指定格式
    formatted_time = current_time.strftime('%Y-%m-%d %H:%M:%S')

    record_file = user_task_path + '/uploadData.txt'
    with open(record_file, 'w') as file:
        file.write(music_name + '\n' + str(formatted_time) + '\n' + task_folder)
    file.close()

else:
    print("No command line arguments provided.")

