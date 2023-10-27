 // 顯示技能下拉選單
  // // otherFile.js
  import { API_DOMAIN } from './config.js';
 function fetchAndPopulateDropdownSkills(apiUrl, selectId) {
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
        // 檢查伺服器響應是否正確
        if (!response.ok) {
          throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
        }
        return response.json(); // 直接解析為JSON
      })
      .then(responseData => {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = ''; // 清空现有选项
    
        responseData.data.forEach(skill => {
            const option = document.createElement('option');
            option.value = skill.id;
            option.textContent = skill.name;
            selectElement.appendChild(option);
        });
    })
    
      .catch(error => {
        console.error("Error fetching data:", error);
      });
  }
  window.fetchAndPopulateDropdownSkills = fetchAndPopulateDropdownSkills;