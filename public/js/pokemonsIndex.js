// 由按鈕觸發,打api接收所有寶可夢資訊
 // // otherFile.js
 import { API_DOMAIN } from './config.js';
function pokemonsIndex() {
  document.getElementById('pokemon-detail').style.display = 'none';
  document.getElementById('pokemonDetail').style.display = 'none';
  document.getElementById('pokemonList').style.display = 'block';
  document.getElementById('ordersIndex').style.display = 'none';
    document.getElementById('orderDetails').style.display = 'none'; 
  const token = localStorage.getItem('jwtToken');

  fetch(`${API_DOMAIN}/api/pokemons/`, {
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

    img.addEventListener('click', function () {
      const pokemonId = this.dataset.id;
      fetchPokemonDetails(pokemonId);
    });
  });
  togglePagination(false);
}

function fetchPokemonDetails(id) {
  const token = localStorage.getItem('jwtToken');
  fetch(`${API_DOMAIN}/api/pokemons/${id}`, {
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
    const pokemonDetail = document.getElementById('pokemon-detail');
    console.log(data.race_id);
pokemonDetail.innerHTML = `
    <img id="pokemon-photo" src="${data.photo}" alt="${data.name} Image">
    <h2 id="pokemon-name">${data.name}</h2>
    <p>種族: <span id="pokemon-race">${data.race}</span></p>
    
    <!-- 能力的下拉選單 -->
    <label>能力:</label>
    <select id="abilitiesDropdown"></select>

    <!-- 性格的下拉選單 -->
    <label>性格:</label>
    <select id="naturesDropdown"></select>

    <!-- 等級的下拉選單 -->
    <label>等級:</label>
    <select id="levelDropdown"></select>

    <!-- 技能1的下拉選單 -->
    <label>技能1:</label>
    <select id="skill1Dropdown"></select>
    
    <!-- 技能2的下拉選單 -->
    <label>技能2:</label>
    <select id="skill2Dropdown"></select>

    <!-- 技能3的下拉選單 -->
    <label>技能3:</label>
    <select id="skill3Dropdown"></select>

    <!-- 技能4的下拉選單 -->
    <label>技能4:</label>
    <select id="skill4Dropdown"></select>
    
    <p>主人: <span id="pokemon-host">${data.host}</span></p>
    <ul id="pokemon-skills"></ul>

    <button onclick="updatePokemons(${data.id},${data.race_id} )" class="mt-6 px-4 py-2 font-bold text-white bg-blue-600 rounded-full hover:bg-blue-700">更改寶可夢屬性</button>
    <button onclick="deletePokemons(${data.id})" class="mt-6 px-4 py-2 font-bold text-white bg-blue-600 rounded-full hover:bg-blue-700">放生吧</button>
`;

// 使用您先前的方法填充這些下拉選單
fetchAndPopulateDropdown(`${API_DOMAIN}/api/abilities`, 'abilitiesDropdown');
fetchAndPopulateDropdown(`${API_DOMAIN}/api/natures`, 'naturesDropdown');
fetchEvolutionLevel(`${API_DOMAIN}/api/races/${data.race_id}/evolutionLevel`, 'levelDropdown');
fetchAndPopulateDropdownSkills(`${API_DOMAIN}/api/races/${data.race_id}/skill`, 'skill1Dropdown');
fetchAndPopulateDropdownSkills(`${API_DOMAIN}/api/races/${data.race_id}/skill`, 'skill2Dropdown');
fetchAndPopulateDropdownSkills(`${API_DOMAIN}/api/races/${data.race_id}/skill`, 'skill3Dropdown');
fetchAndPopulateDropdownSkills(`${API_DOMAIN}/api/races/${data.race_id}/skill`, 'skill4Dropdown');


    document.getElementById('pokemon-detail').style.display = 'block';
    document.getElementById('pokemonList').style.display = 'none';


  }
}

  function updatePokemons(id,raceId) {
    const abilityDropdown = document.getElementById('abilitiesDropdown');
    const natureDropdown = document.getElementById('naturesDropdown');
    const levelDropdown = document.getElementById('levelDropdown');
    const skill1Dropdown = document.getElementById('skill1Dropdown');
    const skill2Dropdown = document.getElementById('skill2Dropdown');
    const skill3Dropdown = document.getElementById('skill3Dropdown');
    const skill4Dropdown = document.getElementById('skill4Dropdown');
    
    const selectedAbility = parseInt(abilityDropdown.options[abilityDropdown.selectedIndex].value);
    const selectedNature = parseInt(natureDropdown.options[natureDropdown.selectedIndex].value);
    const selectedLevel = parseInt(levelDropdown.options[levelDropdown.selectedIndex].value);
    const selectedSkill1 = parseInt(skill1Dropdown.options[skill1Dropdown.selectedIndex].value);
    const selectedSkill2 = parseInt(skill2Dropdown.options[skill2Dropdown.selectedIndex].value);
    const selectedSkill3 = parseInt(skill3Dropdown.options[skill3Dropdown.selectedIndex].value);
    const selectedSkill4 = parseInt(skill4Dropdown.options[skill4Dropdown.selectedIndex].value);

    const skillsArray = [selectedSkill1, selectedSkill2, selectedSkill3, selectedSkill4].filter(skill => !isNaN(skill));

    const updatedData = {
        ability_id: selectedAbility,
        nature_id: selectedNature,
        level: selectedLevel,
        skills: skillsArray,
        race_id: raceId
    };

    // 發送API請求
    sendUpdateRequest(id, updatedData);
}


function sendUpdateRequest(raceId, updatedData) {
  const token = localStorage.getItem('jwtToken');
  fetch(`${API_DOMAIN}/api/pokemons/${raceId}`, {
      method: 'PATCH', // 或其他適當的HTTP方法
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token // 這裡添加token
      },
      body: JSON.stringify(updatedData)
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('pokemon-detail').style.display = 'none'; 
          alert('寶可夢屬性已更新！');
      
  })
  .catch(error => {
      console.error('Error updating pokemon:', error);
  });
}


function deletePokemons(pokemonId){
  const token = localStorage.getItem('jwtToken');
  fetch(`${API_DOMAIN}/api/pokemons/${pokemonId}`, {
      method: 'DELETE', // 或其他適當的HTTP方法
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': 'Bearer ' + token // 這裡添加token
      }})
      .then(data => {
        document.getElementById('pokemon-detail').style.display = 'none';
        alert('寶可夢已放生了！！！');
       
})
}

window.pokemonsIndex = pokemonsIndex;
window.deletePokemons = deletePokemons;
window.updatePokemons = updatePokemons;