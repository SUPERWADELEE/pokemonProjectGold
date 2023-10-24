## **項目名稱**：

寶可夢購物車。

## **項目簡介**：

這個是一個用來線習開發API、前端畫面的專案，後端主要用laravel，前端用html、css、搭配原生js。

## 主要功能：

### 註冊、登入、登出

基本的註冊功能(使用者輸入email及密碼認證)。

登入除了一般登入以外還有第三方登入。

![截圖 2023-10-17 下午10.10.01.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/391eb16c-e84e-470d-8ed5-6abf8d879682/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.10.01.png)

### 寶可夢商品列表

我的商品就是很多的寶可夢。

然後點選寶可夢列表，他會出現一千多隻寶可夢。

點選寶可夢圖片可以加入購物車。

![截圖 2023-10-17 下午10.13.31.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/d33ab481-77a9-4080-b31d-5940dd2037c9/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.13.31.png)

### 購物車選單

加入購物車的寶可夢會進到購物車清單裡

![截圖 2023-10-17 下午10.16.07.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/8109186e-d8a7-460d-9169-bc5f874de293/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.16.07.png)

### 結帳頁面

這裡我是去串藍星金流API。

![截圖 2023-10-17 下午10.18.09.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/8cf03761-f1b4-459c-afef-60ae63da6239/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.18.09.png)

### 結帳成功後出現新增寶可夢頁面

![截圖 2023-10-17 下午10.21.33.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/b23ab123-4b30-43f9-a7db-3dde71b000f0/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.21.33.png)

### 新增完以後點選我的寶可夢會出現剛剛新增的寶可夢

![截圖 2023-10-17 下午10.24.08.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/7e789293-65b1-4b40-bf5e-24b989c60795/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.24.08.png)

### 可以對自己的寶可夢做修改和刪除

![截圖 2023-10-17 下午10.27.27.png](https://prod-files-secure.s3.us-west-2.amazonaws.com/dc365df8-45a7-4023-bb4d-d8ccfc3d31e5/ac575c7c-9909-46ad-8052-9ffafcee3630/%E6%88%AA%E5%9C%96_2023-10-17_%E4%B8%8B%E5%8D%8810.27.27.png)

## 用到的技術

部署的基本(AWS)

Token Authentication

- 使用laravel開發API
    - Ｍiddleware設定
    - 表單驗證
    - 自定義表單驗證
    - 使用resource
    - ORM操作
    - 錯誤處理
- 前端畫面
    - 基本的html表單操作
    - 基本的css設定顏色(tailwind)
    - 基本javascript操作
        - 使用fetch發api，接收並分析回傳資料，操作html渲染畫面
