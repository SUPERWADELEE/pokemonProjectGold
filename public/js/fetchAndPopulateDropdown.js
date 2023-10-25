function fetchAndPopulateDropdown(apiUrl, selectId) {
    const token = localStorage.getItem('jwtToken');
    fetch(apiUrl, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token // 這裡添加token
        }
      })

      .then(response => response.json())
      .then(data => {
        const selectElement = document.getElementById(selectId);
        selectElement.innerHTML = ''; // 清空现有选项
        data.forEach(item => {
          const option = document.createElement('option');
          option.value = item.id // 取决于你的API结构
          option.textContent = item.name;
          selectElement.appendChild(option);
        });
      })
      .catch(error => {
        console.error("Error fetching data:", error);
      });
  }