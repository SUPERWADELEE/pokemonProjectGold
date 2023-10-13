  
  // 購買前讓你看清楚商品並加入購物車
  function PokemonDetail(pokemon) {
    const detailContainer = document.getElementById('pokemonDetail');
    detailContainer.style.display = 'block';
    detailContainer.innerHTML = `
      <label for="pokemonName">寶可夢名稱:</label>
      <input type="text" id="pokemonName" name="pokemonName" >
      <h2 id="race_id" data-id="${pokemon.id}">${pokemon.name}</h2>
      <img id="pokemonImage" src="${pokemon.photo}" alt="${pokemon.name}" width="200">
      <button id="addToCartBtn">加入購物車</button>
      <div id="notification" class="mt-2"></div>
  

      
  `;
  const addToCartBtn = document.getElementById('addToCartBtn');
  const notification = document.getElementById('notification');
  
  addToCartBtn.addEventListener('click', () => {
    const newPokemon = {
      quantity:1,
      race_id:pokemon.id
    };
    const token = localStorage.getItem('jwtToken');
    fetch('http://localhost:8000/api/cart_items', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token ,// 這裡添加token
      'Accept': 'application/json'
    },
    body: JSON.stringify(newPokemon)
  })
    // 更新通知區域的內容
    notification.textContent = `${pokemon.name} 已加入購物車!`;
  });

}
  
  
  
  
  
  
  
  // 購買之後顯示下拉選單標籤,由寶可夢圖片觸發
  function updatePokemonDetail(pokemon) {
    const detailContainer = document.getElementById('pokemonDetail');
    detailContainer.style.display = 'block';
    detailContainer.innerHTML = `
      <label for="pokemonName">寶可夢名稱:</label>
      <input type="text" id="pokemonName" name="pokemonName" >
      <h2 id="race_id" data-id="${pokemon.id}">${pokemon.name}</h2>
      <img id="pokemonImage" src="${pokemon.photo}" alt="${pokemon.name}" width="200">
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
      <label>等級:</label>
      <select id="level"></select>
      <button onclick="createPokemons()" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">新增寶可夢</button>
      <!-- 其他详细信息 -->
  `;


    // 2. 调用函数来填充下拉列表
    // fetchAndPopulateDropdown('API_URL_FOR_SKILLS', 'skills');
    fetchAndPopulateDropdown('http://localhost:8000/api/abilities', 'abilities');
    fetchAndPopulateDropdown('http://localhost:8000/api/natures', 'natures');
    fetchEvolutionLevel(`http://localhost:8000/api/races/${pokemon.id}/evolutionLevel`, 'level');
    fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill1');
    fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill2');
    fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill3');
    fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.id}/skill`, 'skill4');
    // http://localhost:8000/api/races/4/skill

  }