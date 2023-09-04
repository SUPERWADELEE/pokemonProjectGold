// 進化等級下拉選單
    // 這是用來填充選單的函數
    function populateEvolutionDropdown(evolutionLevel, selectId) {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = ''; // 清空現有選項
        if (!evolutionLevel) {
          for (let i = 1; i <= 100; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            selectElement.appendChild(option);
          }
        }
  
        if (evolutionLevel) {
          for (let i = 1; i <= evolutionLevel - 1; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            selectElement.appendChild(option);
  
          }
  
  
        }
      }
  
     
  
      // 這是用來從API獲取進化等級並呼叫上面的函數的函數
      function fetchEvolutionLevel(apiUrl, selectId) {
        const token = localStorage.getItem('jwtToken');
        fetch(apiUrl, {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'Authorization': 'Bearer ' + token // 這裡添加token
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
            }
            return response.json();
          })
          .then(data => {
            const evolutionLevel = data.evolution_level;
            populateEvolutionDropdown(evolutionLevel, selectId);
          })
          .catch(error => {
            console.error("Error fetching data:", error);
          });
      }
  