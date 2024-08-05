# 2024_DistributedOperatingSystem
使用Cloud System、Docker開發
## mining_level1: 用docker 架構實做fake分散式帳本 (查帳、挖礦、轉帳)
<p>語言: python、php</p>
<p>簡單說明如下圖:</p>

![螢幕擷取畫面 2024-08-04 224911](https://github.com/user-attachments/assets/40f6778d-7e3c-4ef4-920b-ff8248e315ac)

## mining_level2: 用docker P2P架構實做分散式帳本 (查帳、挖礦、轉帳)
<p>語言: python、php</p>
<p>簡單說明如下:</p>

![螢幕擷取畫面 2024-08-04 225426](https://github.com/user-attachments/assets/05c9f3d9-f9d8-4a03-a9f0-a6618e889ae3)

<h3>功能說明</h3>
<p>設定每轉帳6次都會產生新區塊</p>
<p>checkMoney: 登入(切進去container)任意二個 clients 查詢餘額</p>
<p>checkLog: 登入(切進去container)任意二個 clients 查詢交易記錄</p>
<p>transaction: 轉帳至其他用戶(所有Node都會同步更新)</p>
<p>checkChain: 當竄改任一Client的一個帳本區塊，能檢查該節點帳本鍊完整性</p>
<p>checkAllChains: 是為了跨節點比對檢查所有Sha256值來確認帳本鍊完整性，說明如下圖</p>

![螢幕擷取畫面 2024-08-04 225512](https://github.com/user-attachments/assets/bede5767-d5b9-4687-8c8e-76c7105bf5fa)
<p>帳本共識機制: 檢查是否有超過50%的不一致性</p>

## mini_project: 分離音樂(人聲、純音樂)，供下載之網站
<h3>使用docker架構及spleeter套件實現</h3>
<p>語言: python、php</p>
<h3>用途:</h3>
<p>1.使用者功能1: 上傳10分鐘內的音樂audio，能分離出人聲及純音樂，並能各自下載(人聲及純音樂)</p>
<p>1.使用者功能2: 查看每份檔案的分離運行情況(Waiting、Computing、Finished)</p>
<p>3.管理者功能: 能檢查各節點的CPU性能情況(供圖表分析)</p>
<h2>簡單顯示如下:</h2>

![螢幕擷取畫面 2024-08-05 115710](https://github.com/user-attachments/assets/556c0d89-bda8-41a7-a863-68593945430d)
![螢幕擷取畫面 2024-08-05 115725](https://github.com/user-attachments/assets/d5e71ca2-77f3-42d7-8696-f28b84a87c92)
![螢幕擷取畫面 2024-08-05 115759](https://github.com/user-attachments/assets/75b777f0-4731-4923-a04a-4435aeb77665)


## final_project: mini_project延伸版
<h3>使用docker架構、spleeter套件、Youtube API、nginx Server及linebot實現</h3>
<p>語言: python、php</p>
<h3>用途:</h3>
<p>1.使用者功能1: 在linebot端搜索Youtube的影片(抓取關鍵詞的Top5)，選擇上傳10分鐘內的音樂audio，能分離出人聲及純音樂，並能各自下載(原音樂、人聲及純音樂)</p>
<p>2.使用者功能2: 同時保留網站端使用，能在移動端把音頻下載至手機端或第三方軟體(如:google drive)保存</p>
<p>3.管理者功能1: 能檢查各節點的CPU性能情況(供圖表分析)</p>
<p>4.管理者功能2: 能在網站端處理各節點的情況(暫停、關閉、開啟)，能新增額外節點</p>
<h2>簡單DEMO如下:</h2>
https://drive.google.com/file/d/13l1J51tASgQ42fYMRYhjsFqrJoca8kYw/view?usp=drive_link

