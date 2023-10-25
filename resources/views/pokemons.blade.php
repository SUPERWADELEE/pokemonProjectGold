<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
  <script src="https://cdn.tailwindcss.com"></script>

  <script src="http://localhost:8000/js/populateEvolutionDropdown.js"></script>
  <script src="http://localhost:8000/js/register.js"></script>
  <script src="http://localhost:8000/js/checkout.js"></script>
  <script src="http://localhost:8000/js/login.js"></script>
  <script src="http://localhost:8000/js/createOrder.js"></script>
  <script src="http://localhost:8000/js/fetchAndPopulateDropdown.js"></script>
  <script src="http://localhost:8000/js/fetchAndPopulateDropdownSkills.js"></script>
  <script src="http://localhost:8000/js/fetchUserAvatar.js"></script>
  
  
  <script src="http://localhost:8000/js/login.js"></script>
  
  <script src="http://localhost:8000/js/logout.js"></script>
  
  <script src="http://localhost:8000/js/ordersIndex.js"></script>
  
  <script src="http://localhost:8000/js/pokemonsIndex.js"></script>
 
  <script src="http://localhost:8000/js/racesIndex.js"></script>
  <script src="http://localhost:8000/js/returnIndex.js"></script>
  <script src="http://localhost:8000/js/shoppingCart.js"></script>
  <script src="http://localhost:8000/js/showPage.js"></script>
  <script src="http://localhost:8000/js/showPurchasedPokemon.js"></script>
  <script src="http://localhost:8000/js/togglePagination.js"></script>
  <script src="http://localhost:8000/js/updatePokemonDetail.js"></script>
  <script src="http://localhost:8000/js/userProfile.js"></script>
  <script src="http://localhost:8000/js/waitEmailVerification.js"></script>
  
  <!-- <script src="http://localhost:8000/fetchUserAvatar.js"></script> -->
  

  
  <style>
    
    #pokemon-detail {
      display: none;
    }

    #registerPage {
      display: none;
    }
  </style>
</head>

<body class="bg-gray-100">

  <!-- 登入畫面 -->
  <div id="loginPage">
    <div class="flex justify-center mt-24">
      <div class="w-1/3 bg-white p-6 rounded shadow-lg">
        <h2 class="text-3xl font-bold mb-4">請登入</h2>
        <input type="text" id="emailInput" placeholder="帳號" class="p-2 w-full mb-4 border rounded">
        <input type="password" id="passwordInput" placeholder="密碼" class="p-2 w-full mb-4 border rounded">
        <button onclick="handleLogin()" class="px-4 py-2 font-bold text-white bg-blue-500 w-full rounded-full hover:bg-blue-600">登入</button>
        <div class="mt-4">
          <button onclick="handleRegister()" class="px-4 py-2 font-bold text-white bg-red-500 w-full rounded-full hover:bg-red-600">註冊</button>
        </div>
        <div class="mt-4">
          <a href="/login/google" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded shadow-sm hover:shadow hover:bg-gray-100">
            <!-- Assuming you'll include an SVG or image for the Google icon. -->
            <img src="data:image/png;base64,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" alt="Google 登入" class="w-6 h-6 mr-2">
            使用Google帳號登入
          </a>
        </div>
      </div>
    </div>
  </div>


  <!-- 註冊畫面 -->
  <div id="registerPage">
    <div class="flex justify-center mt-24">
      <div class="w-1/3 bg-white p-6 rounded shadow-lg">
        <h2 class="text-3xl font-bold mb-4">註冊頁面</h2>
        <input type="text" id="nameInput" placeholder="姓名" class="p-2 w-full mb-4 border rounded">
        <input type="text" id="register_emailInput" placeholder="帳號" class="p-2 w-full mb-4 border rounded">
        <input type="password" id="register_passwordInput" placeholder="密碼" class="p-2 w-full mb-4 border rounded">
        <input type="password" id="register_confirm_passwordInput" placeholder="密碼確認" class="p-2 w-full mb-4 border rounded">
        <button onclick="handleRegister()" class="px-4 py-2 font-bold text-white bg-blue-500 w-full rounded-full hover:bg-blue-600">註冊</button>

        <div>
          <button onclick="handleLogin()" class="px-4 py-2 font-bold text-white bg-red-500 w-full rounded-full hover:bg-blue-600">登入</button>
        </div>


        <a href="/login/google" class="block mt-4 text-center">
          <alt= Google 登入 class="w-6 h-6 inline-block"> 使用Google帳號登入
        </a>
      </div>
    </div>
  </div>

  <!-- 等待頁面 -->
  <div id="waitPage" style="display: none;">
    <h2>等待驗證</h2>
    <p>請檢查您的電子郵件以完成驗證。</p>
    <button onclick="resendVerificationEmail()">重新發送驗證郵件</button>
    <button onclick="redirectToLoginPage()">返回登錄頁面</button>
  </div>


  <!-- 寶可夢的畫面 -->
  <div id="pokemonContainer" style="display: none;">
    <div class="flex justify-end space-x-4 mr-4 mt-4">
      <button onclick="logout()" class="px-4 py-2 font-bold text-white bg-red-500 rounded-full hover:bg-red-600">登出</button>
    </div>
    <div class="flex justify-end space-x-4 mr-4 mt-4">
      <button onclick="fetchShoppingCart()" class="px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-red-600">購物車</button>
    </div>
    <div id="avatarContainer" class="ml-4 mt-4">
      <img id="avatar" src="" alt="User Avatar" class="w-64 h-64 rounded-full">
    </div>



    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
        <div class="max-w-2xl">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">寶可夢清單</h2>
          <button onclick="pokemonsIndex()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">我的寶可夢</button>
          <button onclick="fetchPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">寶可夢商品列表</button>
          <button onclick="ordersIndex()" class="mt-6 px-4 py-2 font-bold text-white bg-red-500 rounded-full hover:bg-blue-600">訂單列表</button>
          <button onclick="userProfile()" class="mt-6 px-4 py-2 font-bold text-white bg-red-500 rounded-full hover:bg-blue-600">個人頁面</button>
        </div>


        <div id="pokemonDetail"></div>



        <!-- Pokemon List Container -->
        <ul role="list" id="pokemonList" class="grid gap-x-8 gap-y-12 grid-cols-3 sm:gap-y-16">

          <!-- Single Pokemon will be appended here -->
        </ul>


        <div id="pokemon-detail"></div>




      </div>
    </div>
  </div>
  <div id="ordersIndex"></div>
  <div id="orderDetails"></div>
  <div id="pagination"></div>
  <div id="shoppingCart"></div>
  <div id="purchasedDetail"></div>
  <div id="userProfile"></div>
  </div>
  </div>

  <script>
    window.onload = function() {
      const urlParams = new URLSearchParams(window.location.search);
      const token = urlParams.get('token');
      if (token) {
        // 存儲 token
        localStorage.setItem('jwtToken', token);


        // 更改 UI 以反映用戶已登入的狀態
        document.getElementById('loginPage').style.display = 'none';
        document.getElementById('pokemonContainer').style.display = 'block';
      }
    }
  </script>

@vite(['resources/css/app.css',
  'resources/js/app.js',
  'resources/js/register.js',
  'resources/js/fetchUserAvatar.js',
  'resources/js/userProfile.js',
  'resources/js/ordersIndex.js',
  'resources/js/showPurchasedPokemon.js',
  'resources/js/checkout.js',
  'resources/js/login.js',
  'resources/js/logout.js',
  'resources/js/waitEmailVerification.js',
  'resources/js/showPage.js',
  'resources/js/shoppingCart.js',
  'resources/js/togglePagination.js',
  'resources/js/pokemonsIndex.js',
  'resources/js/racesIndex.js',
  'resources/js/fetchAndPopulateDropdown.js',
  'resources/js/fetchAndPopulateDropdownSkills.js',
  'resources/js/populateEvolutionDropdown.js',
  'resources/js/updatePokemonDetail.js',
  'resources/js/createPokemons.js'])



  <script>
    // 使用範例：您可以在適當的地方呼叫下面這行代碼
    // fetchEvolutionLevelAndPopulateDropdown('YOUR_API_URL_HERE', 'yourSelectId');

    @isset($paymentData)
    var paymentData = @json($paymentData);

    if (paymentData.Status === 'SUCCESS') {
      // 呼叫你的 updatePokemonDetail 函數
      showPurchasedPokemon();
    }
    @endisset










    // 1. 設置事件監聽器
  </script>










  <!-- // function pokemonIndex() {
  // renderPokemons(currentPage);
  // // 你也可以在這裡添加分頁按鈕
  // }

  // 初始渲染
  // fetchPokemons(); // 這裡我使用fetchPokemons進行初始的數據獲取和渲染 -->
</body>

</html>