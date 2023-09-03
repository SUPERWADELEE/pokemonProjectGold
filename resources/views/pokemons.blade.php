<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
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

      </div>
    </div>
  </div>

  <!-- 寶可夢的畫面 -->
  <div id="pokemonContainer" style="display: none;">
    <div class="flex justify-end space-x-4 mr-4 mt-4">
      <button onclick="logout()" class="px-4 py-2 font-bold text-white bg-red-500 rounded-full hover:bg-red-600">登出</button>
    </div>

    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
        <div class="max-w-2xl">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">寶可夢清單</h2>
          <p class="mt-6 text-lg leading-8 text-gray-600">我家的寶可夢,會後空翻</p>
          <button onclick="pokemonIndex()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">所有寶可夢</button>
          <button onclick="fetchPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">新增寶可夢</button>
        </div>
        <div id="pokemonDetail"></div>

        <!-- Pokemon List Container -->
        <ul role="list" id="pokemonList" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
          <!-- Single Pokemon will be appended here -->
        </ul>
        <div id="pagination"></div>
      </div>
    </div>
  </div>
  <div id="pagination"></div>
  </div>
  </div>

  <script>
    function handleLogin() {
      const email = document.getElementById('emailInput').value;
      const password = document.getElementById('passwordInput').value;

      login(email, password)
        .then(token => {
          // 保存token到localStorage
          localStorage.setItem('jwtToken', token);

          // 顯示寶可夢的界面
          showPokemonPage();
        })
        .catch(error => {
          console.error('Login error:', error.message);
        });
    }

    function login(email, password) {
      return fetch('http://localhost:8000/api/Auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: email,
            password: password
          })
        })
        .then(response => {
          if (response.ok) {
            return response.json();
          } else {
            return response.json().then(data => {
              throw new Error(data.message || 'Unable to login');
            });
          }
        })
        .then(data => {
          return data.token;
        });
    }

    function logout() {


      // 調用API的登出端點
      const token = localStorage.getItem('jwtToken');
      fetch('http://localhost:8000/api/Auth/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
          }
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Logout failed');
          }
          return response.json();
        })
        .then(data => {
          console.log('Logged out successfully');
          showLoginPage()
        })
        .catch(error => {
          console.error('Logout error:', error);
        });
      localStorage.removeItem('jwtToken');




    }



    function showPokemonPage() {
      // 隱藏登錄界面
      document.getElementById('loginPage').style.display = 'none';

      // 顯示寶可夢的界面
      document.getElementById('pokemonContainer').style.display = 'block';
    }



    function showLoginPage() {

      // 隱藏登錄界面
      document.getElementById('loginPage').style.display = 'block';

      // 顯示寶可夢的界面
      document.getElementById('pokemonContainer').style.display = 'none';
    }




    // 由按鈕觸發,打api接收所有寶可夢資訊
    function pokemonIndex() {
      const token = localStorage.getItem('jwtToken');

      fetch('http://127.0.0.1:8000/api/pokemons/', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
          }
        })
        .then(response => response.json())
        .then(data => {
          populatePokemons(data.data);
          console.log('Success:', data);
        })
        .catch((error) => {
          console.error('Error:', error);
        });
    }


    // 處理接收到的json擋,然後找到想填入的標籤,創建標籤
    function populatePokemons(pokemons) {
      // 找到想填入的標籤  創建該標籤物件好操作此標籤
      const pokemonList = document.getElementById('pokemonList');
      pokemonList.innerHTML = ''; // 清空列表

      // 個別取出整包資料
      pokemons.forEach(pokemon => { // 注意這裡直接使用 pokemons 來遍歷資料
        // 創建一個li標籤
        const listItem = document.createElement('li');
        // 新增css樣式到這個標籤
        listItem.classList.add('bg-white', 'rounded-lg', 'shadow', 'p-4', 'flex', 'flex-col', 'items-center');
        // 將標籤裡面實際內容填入
        listItem.innerHTML = `
      <a href="/pokemon/${pokemon.id}">
          <img class="h-48 w-48 rounded-full mb-4" src="${pokemon.photo}" alt="${pokemon.name}">
      </a>

          <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">${pokemon.name}</h3>
          <p class="text-sm text-gray-600">種族: ${pokemon.race}</p>
          <p class="text-sm text-gray-600">能力: ${pokemon.ability}</p>
          <p class="text-sm text-gray-600">等級: ${pokemon.level}</p>
          <p class="text-sm text-gray-600">主人: ${pokemon.host}</p>
      `;
        // 將此新創建的list加入到一開始先創建好的html標籤
        // 將創建的動態 DOM 元素附加到現有的 DOM 元素中
        pokemonList.appendChild(listItem);
      });
    }










    // 新增寶可夢


    let pokemons = [];
    const pokemonsPerPage = 10;
    let currentPage = 1;

    // 取得所有寶可夢種族及圖片
    function fetchPokemons() {
      const token = localStorage.getItem('jwtToken');
      fetch('http://127.0.0.1:8000/api/races/', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
          }
        })
        .then(response => response.json())
        .then(data => {
          console.log('Success:', data);
          pokemons = data;
          renderPokemons(currentPage);
          renderPaginationButtons(); // 渲染分頁按鈕
        })
        .catch((error) => {
          console.error('Error:', error);
        });
    }


    // 顯示所有種族姓名及圖片
    function renderPokemons(page) {
      const start = (page - 1) * pokemonsPerPage;
      const end = start + pokemonsPerPage;
      const pokemonsToDisplay = pokemons.slice(start, end);

      const pokemonList = document.getElementById('pokemonList');
      pokemonList.innerHTML = '';

      pokemonsToDisplay.forEach(pokemon => {
        const li = document.createElement('li');
        li.innerHTML = `
      <h3>${pokemon.name}</h3>
      <img src="${pokemon.photo}" alt="${pokemon.name}" width="100">
    `;
        li.addEventListener('click', () => updatePokemonDetail(pokemon)); // 添加点击事件
        pokemonList.appendChild(li);
      });
    }


    function renderPaginationButtons() {
      const paginationContainer = document.getElementById('pagination');
      paginationContainer.innerHTML = '';

      const totalPages = Math.ceil(pokemons.length / pokemonsPerPage);
      for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.addEventListener('click', () => {
          currentPage = i;
          renderPokemons(currentPage);
        });
        paginationContainer.appendChild(button);
      }
    }





    // 1. 创建获取数据并填充下拉列表的函数

    function fetchAndPopulateDropdown(apiUrl, selectId) {
      const token = localStorage.getItem('jwtToken');
      fetch(apiUrl, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
          }
        })

        .then(response => response.json())
        .then(data => {
          const selectElement = document.getElementById(selectId);
          selectElement.innerHTML = ''; // 清空现有选项
          data.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id || item.name; // 取决于你的API结构
            option.textContent = item.name;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error("Error fetching data:", error);
        });
    }


    // 顯示技能下拉選單
    function fetchAndPopulateDropdownSkills(apiUrl, selectId) {
      const token = localStorage.getItem('jwtToken');
      fetch(apiUrl, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
          }
        })
        .then(response => {
          // 檢查伺服器響應是否正確
          if (!response.ok) {
            throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
          }
          return response.json(); // 直接解析為JSON
        })
        .then(responseData => {
          console.log(responseData); // 打印已解析的JSON數據
          const selectElement = document.getElementById(selectId);
          selectElement.innerHTML = ''; // 清空现有选项

          responseData.data.skills.forEach(skill => {
            const option = document.createElement('option');
            option.value = skill.id;
            option.textContent = skill.name;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error("Error fetching data:", error);
        });
    }




// 下拉選單標籤,由寶可夢圖片觸發
    function updatePokemonDetail(pokemon) {
      const detailContainer = document.getElementById('pokemonDetail');
      detailContainer.innerHTML = `
        <label for="pokemonName">寶可夢名稱:</label>
        <input type="text" id="pokemonName" name="pokemonName" >
        <h2>${pokemon.name}</h2>
        <img src="${pokemon.photo}" alt="${pokemon.name}" width="200">
        <label>技能1:</label>
        <select id="skill1"></select>
        <label>技能2:</label>
        <select id="skill2"></select>
        <label>技能3:</label>
        <select id="skill3"></select>
        <label>技能4:</label>
        <select id="skill4"></select>
        <label>特性:</label>
        <select id="abilities"></select>
        <label>性格:</label>
        <select id="natures"></select>
        <button onclick="createPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">新增寶可夢</button>
        <!-- 其他详细信息 -->
    `;


      // 2. 调用函数来填充下拉列表
      // fetchAndPopulateDropdown('API_URL_FOR_SKILLS', 'skills');
      fetchAndPopulateDropdown('http://localhost:8000/api/abilities', 'abilities');
      fetchAndPopulateDropdown('http://localhost:8000/api/natures', 'natures');
      fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill1');
      fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill2');
      fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill3');
      fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill4');

    }


    


    
  </script>










  <!-- // function pokemonIndex() {
  // renderPokemons(currentPage);
  // // 你也可以在這裡添加分頁按鈕
  // }

  // 初始渲染
  // fetchPokemons(); // 這裡我使用fetchPokemons進行初始的數據獲取和渲染 -->
</body>

</html>