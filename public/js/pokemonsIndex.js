 // 由按鈕觸發,打api接收所有寶可夢資訊
 function pokemonsIndex() {
  document.getElementById('pokemon-detail').style.display = 'none';
  document.getElementById('pokemonList').style.display = 'block';
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
    const pokemonList = document.getElementById('pokemonList');
    pokemonList.innerHTML = ''; // 清空列表

    pokemons.forEach(pokemon => {
      const listItem = document.createElement('li');
      listItem.classList.add('bg-white', 'rounded-lg', 'shadow', 'p-4', 'flex', 'flex-col', 'items-center');

      listItem.innerHTML = `
        <img class="h-48 w-48 rounded-full mb-4 pokemon-image" data-id="${pokemon.id}" src="${pokemon.photo}" alt="${pokemon.name}">
        <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">${pokemon.name}</h3>
        <p class="text-sm text-gray-600">種族: ${pokemon.race}</p>
        <p class="text-sm text-gray-600">能力: ${pokemon.ability}</p>
        <p class="text-sm text-gray-600">等級: ${pokemon.level}</p>
        <p class="text-sm text-gray-600">主人: ${pokemon.host}</p>
      `;

      pokemonList.appendChild(listItem);

      // 在這裡為圖片添加事件監聽器
      // 也就是說也就是說這裡是我想在圖片加上連結
      const img = listItem.querySelector('.pokemon-image');
      img.addEventListener('click', function() {
        const pokemonId = this.dataset.id;
        fetchPokemonDetails(pokemonId);
      });
    });
    togglePagination(false);
}

function fetchPokemonDetails(id) {
  const token = localStorage.getItem('jwtToken');
  fetch(`http://127.0.0.1:8000/api/pokemons/${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + token // 這裡添加token
    }
  })
    
    .then(response => response.json())
    .then(details => {
      // 使用上面定義的函數來更新畫面
      updatePokemonDetail(details.data);
  })
    .catch(error => {
        console.error('Error fetching details:', error);
    });


    function updatePokemonDetail(data) {
      document.getElementById('pokemon-photo').src = data.photo;
      document.getElementById('pokemon-name').textContent = data.name;
      document.getElementById('pokemon-race').textContent = data.race;
      document.getElementById('pokemon-ability').textContent = data.ability;
      document.getElementById('pokemon-level').textContent = data.level;
      document.getElementById('pokemon-host').textContent = data.host;
  
      // 更新技能列表
      const skillsList = document.getElementById('pokemon-skills');
      skillsList.innerHTML = '';  // 先清空列表
      data.skills.forEach(skill => {
          const listItem = document.createElement('li');
          listItem.textContent = skill;
          skillsList.appendChild(listItem);
      });

      document.getElementById('pokemon-detail').style.display = 'block';
      document.getElementById('pokemonList').style.display = 'none';

     
  }
  
}

  