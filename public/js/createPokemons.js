document.querySelector('button').addEventListener('click', createPokemons);

function createPokemons() {
  // 2. 獲取用戶輸入
  const pokemonName = document.getElementById('pokemonName').value;

  const skill1 = document.getElementById('skill1').value;
  const skill2 = document.getElementById('skill2').value;
  const skill3 = document.getElementById('skill3').value;
  const skill4 = document.getElementById('skill4').value;

  const abilities = document.getElementById('abilities').value;
  const natures = document.getElementById('natures').value;
  const level = document.getElementById('level').value;
  const race_id = document.getElementById('race_id').getAttribute('data-id');



  const newPokemon = {
    name: pokemonName,
    skills: [skill1, skill2, skill3, skill4],
    ability_id: abilities,
    nature_id: natures,
    level:level,
    race_id:race_id
    // ... 其他參數
  };

  // 3. 創建和發送請求
  const token = localStorage.getItem('jwtToken');
  fetch('http://localhost:8000/api/pokemons', {
    
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token // 這裡添加token
      },
      body: JSON.stringify(newPokemon)
    })
    .then(response => response.json())
    .then(data => {
      
        alert('寶可夢成功新增！');
      
    })
    .catch(error => {
      console.error('Error:', error);
      alert('伺服器錯誤！');
    });
}