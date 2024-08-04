# remote computer
# Notice: change http for ngrok link

# flask
from flask import Flask
app = Flask(__name__)
from flask import request, abort

# line bot 
# pip install line-bot-sdk
from linebot import  LineBotApi, WebhookHandler
from linebot.exceptions import InvalidSignatureError
from linebot.models import MessageEvent, TextMessage, TextSendMessage, FlexSendMessage, TemplateSendMessage, ButtonsTemplate, URIAction

# 爬youtube
import ssl
ssl._create_default_https_context = ssl._create_stdlib_context
import os
from pytube import YouTube
from googleapiclient.discovery import build

# CHANNEL_ACCESS_TOKEN
line_bot_api = LineBotApi('')
# CHANNEL_SECRET
handler = WebhookHandler('')

# 替換成你的 YouTube Data API 密鑰
api_key = ''

# 建立 YouTube 服務對象
youtube = build('youtube', 'v3', developerKey=api_key)


# php網址
http = 'https://cc72-118-150-119-223.ngrok-free.app'
line_login_page = '/cloudsystem/line_login.php'
add_job = '/cloudsystem/add_job.php'

def search_videos(query):
    # 調用 YouTube Data API 的 search.list 方法
    request = youtube.search().list(
        part='snippet',
        q=query+' lyrics',
        type='video',
        maxResults=5  # 獲取前5個結果
    )
    response = request.execute()

    # 處理並返回搜尋結果
    results = []
    for item in response['items']:
        video_id = item['id']['videoId']
        title = item['snippet']['title']
        thumbnails = item['snippet']['thumbnails']
        thumbnail_url = thumbnails['medium']['url']  # 使用中等尺寸的縮圖
        url = f'https://www.youtube.com/watch?v={video_id}'
        results.append({'title': title, 'thumbnail_url': thumbnail_url, 'url': url})
    return results
    

### 監聽所有來自 /callback 的 Post Request
@app.route("/callback", methods=['POST'])
def callback():
    # get X-Line-Signature header value｀
    signature = request.headers['X-Line-Signature']

    # get request body as text
    body = request.get_data(as_text=True)
    # app.logger.info("Request body: " + body)

    # handle webhook body
    try:
        handler.handle(body, signature)
    except InvalidSignatureError:
        abort(400)

    return 'OK'

### 訊息傳遞區塊
@handler.add(MessageEvent, message=TextMessage)  # 判斷訊息類別
def handle_message(event):
    user_text = event.message.text  # 使用者傳給 bot 的東東
    user_text = user_text.split('@')
    user_id = event.source.user_id  # 使用者的line user id

    if user_text[0] == '使用規範':
        message_text = '本系統可以為您將音樂進行下載、分離人聲的工作，您可以點選圖文選單進行更深入的了解。'
        message = TextSendMessage(text=message_text)
        line_bot_api.reply_message(event.reply_token, message)

    elif user_text[0] == '搜尋音樂':
        message = TextSendMessage(text="請輸入詳細的內容，例如歌手或歌曲，若想要更精確的結果，請搜尋歌手+歌曲，輸入規範如下：\n請搜尋@關鍵字\n例如：請搜尋@鄧紫棋 句號")
        line_bot_api.reply_message(event.reply_token, message)

    elif user_text[0] == '下載音樂':
        # 構建 URL
        data = {
            'user_id': user_id
        }
        url = http + line_login_page

        url_with_data = url + '?' + '&'.join([f'{key}={value}' for key, value in data.items()])

        # 構建按鈕模板訊息
        buttons_template = ButtonsTemplate(
            title='下載連結',
            text='點擊這裡前往下載的網頁',
            actions=[
                URIAction(label='前往下載音樂', uri=url_with_data)
            ]
        )

        template_message = TemplateSendMessage(
            alt_text='Buttons template',
            template=buttons_template
        )

        # 發送訊息
        line_bot_api.push_message(user_id, template_message)

    elif user_text[0] == '請搜尋':
        search_results = search_videos(user_text[1])
        flex_message = {
            "type": "carousel",
            "contents": []}
        for index, result in enumerate(search_results):
            new_bubble = {
                "type": "bubble",
                "size": "micro",
                "hero": {
                    "type": "image",
                    "url": f"{result['thumbnail_url']}",
                    "size": "full",
                    "aspectMode": "cover",
                    "aspectRatio": "320:213"
                },
                "body": {
                    "type": "box",
                    "layout": "vertical",
                    "contents": [
                    {
                        "type": "text",
                        "text": f"{result['title']}",
                        "weight": "bold",
                        "size": "sm",
                        "wrap": True
                    },
                    {
                        "type": "box",
                        "layout": "vertical",
                        "contents": [
                        {
                            "type": "button",
                            "action": {
                            "type": "uri",
                            "label": "觀看影片",
                            "uri": f"{result['url']}"
                            }
                        }
                        ]
                    },
                    {
                        "type": "box",
                        "layout": "vertical",
                        "contents": [
                        {
                            "type": "button",
                            "action": {
                            "type": "message",
                            "label": "我要這個",
                            "text": f"請將這首歌加入工作@{result['url']}"
                            }
                        }
                        ]
                    }
                    ],
                    "spacing": "sm",
                    "paddingAll": "13px"
                }
            }
            flex_message["contents"].append(new_bubble)
            
        line_bot_api.reply_message(event.reply_token, FlexSendMessage("Results", flex_message))
    
    elif user_text[0] == '請將這首歌加入工作':
        music_id = user_text[1].split('v=')[1]
        data = {
            'user_id': user_id,
            'job_url_id': music_id
        }
        url = http + add_job
        url_with_data = url + '?' + '&'.join([f'{key}={value}' for key, value in data.items()])
        # 構建按鈕模板訊息
        buttons_template = ButtonsTemplate(
            title='確認您的音樂',
            text='點擊這裡確認您的音樂名稱，請注意：名稱只允許全英文',
            actions=[
                URIAction(label='確認我的音樂', uri=url_with_data)
            ]
        )

        template_message = TemplateSendMessage(
            alt_text='Buttons template',
            template=buttons_template
        )

        # 發送訊息
        line_bot_api.push_message(user_id, template_message)

    elif user_text[0] == '謝謝使用':
        message = TextSendMessage(text="謝謝您的使用！")
        line_bot_api.reply_message(event.reply_token, message)

    
if __name__ == '__main__':
    port = int(os.environ.get('PORT', 5000))  # 這邊的 port 要跟跑 ngrok 的 port 相同
    app.run(host='0.0.0.0', port=port, debug=True )
