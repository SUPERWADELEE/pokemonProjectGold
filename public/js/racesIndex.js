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
   }