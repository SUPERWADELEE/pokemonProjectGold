// document.querySelector('pokemonImage').addEventListener('click', createPokemons);

function createPokemons(raceId, buttonElement) {
  // 2. 獲取用戶輸入
  const pokemonName = document.getElementById(`pokemonName${raceId}`).value;

  const skill1 = document.getElementById(`skill1${raceId}`).value;
  const skill2 = document.getElementById(`skill2${raceId}`).value;
  const skill3 = document.getElementById(`skill3${raceId}`).value;
  const skill4 = document.getElementById(`skill4${raceId}`).value;

  const abilities = document.getElementById(`abilities${raceId}`).value;
  const natures = document.getElementById(`natures${raceId}`).value;
  const level = document.getElementById(`level${raceId}`).value;
  const race_id = document.getElementById(`race_id${raceId}`).getAttribute('data-id');

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
  fetch('https://wade.monster/api/pokemons', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token, // 這裡添加token
      'Accept': 'application/json'
    },
    body: JSON.stringify(newPokemon)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    
    // 無論回應內容如何，只要狀態碼是200，就隱藏 div
    const currentDiv = buttonElement.closest('div');
    if (currentDiv) {
      currentDiv.style.display = 'none';
    } else {
      console.warn('未找到要隱藏的div元素');
    }
    
    // 顯示成功通知
    alert('新增成功！請新增下一隻寶可夢。');
  })
  .catch(error => {
    console.error('Error:', error);
    alert('新增失敗，請稍後再試。');
  });
  
  


// .then(data => {
//   // 創建一個表單元素
//   const form = document.createElement('form');
//   form.method = 'POST';
//   form.action = data.payment_url;

//   // 添加可見的輸入字段到表單
//   const fieldInfo = [
//     { name: 'MerchantID', value: data.mid, label: 'MID' },
//     { name: 'Version', value: '2.0', label: 'Version' },
//     { name: 'TradeInfo', value: data.edata1, label: 'TradeInfo' },
//     { name: 'TradeSha', value: data.hash, label: 'TradeSha' }
//   ];

//   fieldInfo.forEach(field => {
//     const label = document.createElement('label');
//     label.textContent = `${field.label}: `;

//     const input = document.createElement('input');
//     input.name = field.name;
//     input.value = field.value;
//     input.readOnly = true;

//     form.appendChild(label);
//     form.appendChild(input);
//     form.appendChild(document.createElement('br'));
//   });

//   const submitButton = document.createElement('input');
//   submitButton.type = 'submit';
//   form.appendChild(submitButton);

//   // 將表單附加到body
//   document.body.appendChild(form);

//   // 使用 JavaScript 自動提交表單
//   form.submit();
// })


  

//   .then(response => {
//     // 如果响应的 HTTP 状态码不是2xx，抛出错误
//     if (!response.ok) {
//       // 把响应主体解析为 JSON，然后抛出错误
//       return response.json().then(err => { throw err; });
//     }
//     return response.json();
//   })
//   .then(data => {
//     alert('寶可夢成功新增！');
//     const detailContainer = document.getElementById('pokemonDetail');
//     detailContainer.style.display = 'none';
//   })
//   .catch(error => {
//     console.error('Error:', error);
//     if (error && error.message) {
//       alert(`伺服器錯誤：${error.message}`);
//     } else {
//       alert('伺服器錯誤！');
//     }
//   });

}