<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
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
        <div>
          <button onclick="handleRegister()" class="px-4 py-2 font-bold text-white bg-red-500 w-full rounded-full hover:bg-blue-600">註冊</button>
        </div>


        <a href="/login/google" class="block mt-4 text-center">
          <alt= Google 登入 class="w-6 h-6 inline-block"> 使用Google帳號登入
        </a>
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

    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
        <div class="max-w-2xl">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">寶可夢清單</h2>
          <p class="mt-6 text-lg leading-8 text-gray-600">我家的寶可夢,會後空翻</p>
          <button onclick="pokemonsIndex()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">所有寶可夢</button>
          <button onclick="fetchPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">寶可夢商品列表</button>
        </div>


        <div id="pokemonDetail"></div>
      


        <!-- Pokemon List Container -->
        <ul role="list" id="pokemonList" class="grid gap-x-8 gap-y-12 grid-cols-3 sm:gap-y-16">

          <!-- Single Pokemon will be appended here -->
        </ul>


        <div id="pokemon-detail">
          <img id="pokemon-photo" src="" alt="Pokemon Image">
          <h2 id="pokemon-name"></h2>
          <p>種族: <span id="pokemon-race"></span></p>
          <p>能力: <span id="pokemon-ability"></span></p>
          <p>等級: <span id="pokemon-level"></span></p>
          <p>主人: <span id="pokemon-host"></span></p>
          <ul id="pokemon-skills"></ul>
        </div>


      </div>
    </div>
  </div>
  <div id="pagination"></div>
  <div id="shoppingCart"></div>
  <div id="purchasedDetail"></div>
  </div>
  </div>

  <script>
    window.onload = function() {
      const urlParams = new URLSearchParams(window.location.search);
      const token = urlParams.get('token');
      if (token) {
        // 存儲 token
        localStorage.setItem('jwtToken', token);

        console.log(token);
        // 更改 UI 以反映用戶已登入的狀態
        document.getElementById('loginPage').style.display = 'none';
        document.getElementById('pokemonContainer').style.display = 'block';
      }
    }
   
    

    
  </script>
   <script src="{{ Vite::asset('resources/js/createOrder.js') }}"></script> 
 <script src="{{ Vite::asset('resources/js/returnIndex.js') }}"></script> 
  <script src="{{ Vite::asset('resources/js/showPurchasedPokemon.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/checkout.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/login.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/register.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/logout.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/waitEmailVerification.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/showPage.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/shoppingcart.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/togglePagination.js') }}"></script>
  <!-- <script>togglePagination(false);</script> -->
  <script src="{{ Vite::asset('resources/js/pokemonsIndex.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/racesIndex.js') }}"></script>
  <!-- // 1. 创建获取数据并填充下拉列表的函数 -->
  <script src="{{ Vite::asset('resources/js/fetchAndPopulateDropdown.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/fetchAndPopulateDropdownSkills.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/populateEvolutionDropdown.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/updatePokemonDetail.js') }}"></script>
  <script src="{{ Vite::asset('resources/js/createPokemons.js') }}"></script>

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