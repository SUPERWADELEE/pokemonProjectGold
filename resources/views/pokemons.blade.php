<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
      <div class="max-w-2xl">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">寶可夢清單</h2>
        <p class="mt-6 text-lg leading-8 text-gray-600">我家的寶可夢,會後空翻</p>
        <button onclick="pokemonIndex()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">所有寶可夢</button>
        <button onclick="fetchPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">新增寶可夢</button>

      </div>



      <!-- Pokemon List Container -->
      <ul role="list" id="pokemonList" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">



        <!-- Single Pokemon will be appended here -->

      </ul>
      <div id="pagination"></div>
    </div>
  </div>

  <script>
    function pokemonIndex() {
      fetch('http://127.0.0.1:8000/api/pokemons/', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
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

    function populatePokemons(pokemons) {
      const pokemonList = document.getElementById('pokemonList');
      pokemonList.innerHTML = ''; // 清空列表

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
        `;
        // 將此新創建的list加入到一開始先創建好的html標籤
        // 將創建的動態 DOM 元素附加到現有的 DOM 元素中
        pokemonList.appendChild(listItem);
      });
    }

    let pokemons = [];
    const pokemonsPerPage = 10;
    let currentPage = 1;

    function fetchPokemons() {
      fetch('http://127.0.0.1:8000/api/races/', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
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

    fetchPokemons();


    // function pokemonIndex() {
    //   renderPokemons(currentPage);
    //   // 你也可以在這裡添加分頁按鈕
    // }

    // 初始渲染
    // fetchPokemons();  // 這裡我使用fetchPokemons進行初始的數據獲取和渲染
  </script>
</body>

</html>