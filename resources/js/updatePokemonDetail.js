  
  // 購買前讓你看清楚商品並加入購物車
  function PokemonDetail(pokemon) {
    const detailContainer = document.getElementById('pokemonDetail');
    detailContainer.style.display = 'block';
    detailContainer.innerHTML = `
    <h2 id="race_id" data-id="${pokemon.id}" class="text-xl font-bold mb-4">${pokemon.name}</h2>
    <img id="pokemonImage" src="${pokemon.photo}" alt="${pokemon.name}" width="200" class="mb-4 rounded shadow">
    <button id="addToCartBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full focus:outline-none focus:shadow-outline-blue transition duration-200">加入購物車</button>
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
  function updatePokemonDetails(pokemons) {
    document.getElementById('purchasedDetail').style.display = 'block';
    const detailContainer = document.getElementById('purchasedDetail');
    detailContainer.style.display = 'block';
    detailContainer.innerHTML = ''; // 清空原有內容
    let orderDetails = [];
    pokemons.forEach(pokemon => {
        const pokemonDiv = document.createElement('div'); // 為每個寶可夢創建一個新的div
        
        pokemonDiv.innerHTML = `
        <label for="pokemonName${pokemon.race_id}">寶可夢名稱:</label>
        <input type="text" id="pokemonName${pokemon.race_id}" name="pokemonName${pokemon.race_id}" >
        <h2 id="race_id${pokemon.race_id}" data-id="${pokemon.race_id}">${pokemon.race_name}</h2>
        <img id="pokemonImage${pokemon.race_id}" src="${pokemon.race_photo}" alt="${pokemon.race_name}" width="200">
        <label>技能1:</label>
        <select id="skill1${pokemon.race_id}"></select>
        <label>技能2:</label>
        <select id="skill2${pokemon.race_id}"></select>
        <label>技能3:</label>
        <select id="skill3${pokemon.race_id}"></select>
        <label>技能4:</label>
        <select id="skill4${pokemon.race_id}"></select>
        <label>特性:</label>
        <select id="abilities${pokemon.race_id}"></select>
        <label>性格:</label>
        <select id="natures${pokemon.race_id}"></select>
        <label>等級:</label>
        <select id="level${pokemon.race_id}"></select>
        <button onclick="createPokemons(${pokemon.race_id}, this)" class="mt-6 px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-600">新增寶可夢</button>

    `;

  orderDetails.push(pokemon.race_id);
  detailContainer.appendChild(pokemonDiv);

  // fetchAndPopulateDropdown('http://localhost:8000/api/abilities', 'abilities');
  // fetchAndPopulateDropdown('http://localhost:8000/api/natures', 'natures');
  // fetchEvolutionLevel(`http://localhost:8000/api/races/${pokemon.race_id}/evolutionLevel`, 'level');
  // fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, 'skill2');
  // fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, 'skill3');
  // fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, 'skill4');
  // fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, 'skill1');
  // 為每一個技能和屬性創建下拉菜單
const abilityDropdownId = `abilities${pokemon.race_id}`;
const natureDropdownId = `natures${pokemon.race_id}`;
const levelDropdownId = `level${pokemon.race_id}`;
const skill1DropdownId = `skill1${pokemon.race_id}`;
const skill2DropdownId = `skill2${pokemon.race_id}`;
const skill3DropdownId = `skill3${pokemon.race_id}`;
const skill4DropdownId = `skill4${pokemon.race_id}`;

fetchAndPopulateDropdown('http://localhost:8000/api/abilities', abilityDropdownId);
fetchAndPopulateDropdown('http://localhost:8000/api/natures', natureDropdownId);
fetchEvolutionLevel(`http://localhost:8000/api/races/${pokemon.race_id}/evolutionLevel`, levelDropdownId);
fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, skill2DropdownId);
fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, skill3DropdownId);
fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, skill4DropdownId);
fetchAndPopulateDropdownSkills(`http://localhost:8000/api/races/${pokemon.race_id}/skill`, skill1DropdownId);

});
localStorage.setItem('orderDetails', JSON.stringify(orderDetails));
const returnButton = document.createElement('button');
returnButton.innerText = '新增完成';  
returnButton.onclick = createOrder;

// Adding Tailwind CSS styles to the button
returnButton.classList.add(
    'bg-green-500', 
    'hover:bg-green-600', 
    'text-white', 
    'font-bold', 
    'py-2', 
    'px-4', 
    'rounded-full', 
    'focus:outline-none', 
    'focus:shadow-outline-green', 
    'transition', 
    'duration-200'
);

detailContainer.appendChild(returnButton);

}