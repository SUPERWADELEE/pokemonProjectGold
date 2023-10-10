   // 新增寶可夢


   let pokemons = [];
   const pokemonsPerPage = 10;
   let currentPage = 1;

   // 取得所有寶可夢種族及圖片
   function fetchPokemons() {
     const token = localStorage.getItem('jwtToken');
     fetch('http://localhost:8000/api/races/', {
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
    //  顯示從現在當前數再到結束
     const pokemonsToDisplay = pokemons.slice(start, end);

     const pokemonList = document.getElementById('pokemonList');
     pokemonList.innerHTML = '';

     pokemonsToDisplay.forEach(pokemon => {
      const li = document.createElement('li');
        
      li.className = 'w-1/3 p-4 border rounded-lg mb-2 hover:bg-gray-200 cursor-pointer text-center'; // 使用 Tailwind CSS 樣式
      
      li.innerHTML = `
          <h3 class="text-xl font-bold">${pokemon.name}</h3>
          <img src="${pokemon.photo}" alt="${pokemon.name}" class="w-24 mt-2">
      `;
      li.addEventListener('click', () => updatePokemonDetail(pokemon)); 
      pokemonList.appendChild(li);
  });
  
    
   }


  //  分頁功能
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

    togglePagination(true);  // 將這行移出for迴圈
}


   