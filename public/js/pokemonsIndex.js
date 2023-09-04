 // 由按鈕觸發,打api接收所有寶可夢資訊
 function pokemonsIndex() {
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