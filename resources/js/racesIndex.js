let pokemons = [];
let currentPage = 1;

// 取得所有寶可夢種族及圖片
function fetchPokemons() {
    document.getElementById('pagination').style.display = 'block';
    document.getElementById('ordersIndex').style.display = 'none';
    document.getElementById('orderDetails').style.display = 'none';
    document.getElementById('pokemonList').style.display = 'block'; 
    const token = localStorage.getItem('jwtToken');
    fetch(`http://localhost:8000/api/races?page=${currentPage}`, {  // 加入 ?page= 查詢參數
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
        const pokemons = data.data;  // 使用 data.data
        renderPokemons(pokemons);
        const totalPages = data.last_page; 

        renderPaginationButtons(totalPages);

         // 使用 data.last_page 來取得總頁數
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

// 顯示所有種族姓名及圖片
function renderPokemons(pokemons) {
    const pokemonList = document.getElementById('pokemonList');
    pokemonList.innerHTML = '';

    pokemons.forEach(pokemon => {
        const li = document.createElement('li');
        
        li.className = 'w-1/3 p-4 border rounded-lg mb-2 hover:bg-gray-200 cursor-pointer text-center'; // 使用 Tailwind CSS 樣式
        
        li.innerHTML = `
            <h3 class="text-xl font-bold">${pokemon.name}</h3>
            <img src="${pokemon.photo}" alt="${pokemon.name}" class="w-24 mt-2">
        `;
        li.addEventListener('click', () => PokemonDetail(pokemon)); 
        pokemonList.appendChild(li);
    });
}

// 分頁功能
function renderPaginationButtons(totalPages) {
  const paginationContainer = document.getElementById('pagination');
  paginationContainer.innerHTML = '';

  console.log("Total pages:", totalPages);  // Add debug log

  for (let i = 1; i <= totalPages; i++) {
      const button = document.createElement('button');
      button.textContent = i;
    //   button.addEventListener('click', () => {
    //       currentPage = i;
    //       renderPokemons(currentPage);
    //   });
      button.addEventListener('click', () => {
        currentPage = i;
        fetchPokemons();  // 這將會根據新的 currentPage 值從API重新獲取數據
    });
    
      paginationContainer.appendChild(button);
  }

  // 下一頁按鈕
  if (currentPage < totalPages) {
      const nextButton = document.createElement('button');
      nextButton.textContent = '下一頁';
      nextButton.addEventListener('click', () => {
          currentPage++;
          fetchPokemons();
      });
      paginationContainer.appendChild(nextButton);
  }
}



