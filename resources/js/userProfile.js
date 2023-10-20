function userProfile(){
    document.getElementById('pokemonContainer').style.display = 'none';
    document.getElementById('userProfile').style.display = 'block';
    document.getElementById('ordersIndex').style.display = 'none';
    document.getElementById('pagination').style.display = 'none';
    const apiURL = 'http://localhost:8000/api/user';
    const token = localStorage.getItem('jwtToken');
    fetch(apiURL, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token // 這裡添加token
        }
    })
        .then(response => {
            // 檢查響應是否成功
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();  // 解析返回的JSON數據
        })

        .then(data=>{
            showUserDetails(data);
        })
    }

    function showUserDetails(userData){
        const userProfile = document.getElementById('userProfile');
        userProfile.innerHTML = '';
    
        // 創建一個 div 來包裹用戶的資料
        const userDiv = document.createElement('div');
        userDiv.classList.add("flex", "justify-center", "mt-24");
    
        const userDetailsContainer = document.createElement('div');
        userDetailsContainer.classList.add("w-1/3", "bg-white", "p-6", "rounded", "shadow-lg");
    
        userDetailsContainer.innerHTML = `
            <h2 class="text-3xl font-bold mb-4">使用者資訊</h2>
            
            <label for="userName" class="block font-bold mb-2">名稱:</label>
            <input type="text" id="userName" name="userName" value="${userData.name}" class="p-2 w-full mb-4 border rounded">
            
            <label for="userEmail" class="block font-bold mb-2">電子郵件:</label>
            <input type="text" id="userEmail" name="userEmail" value="${userData.email}" class="p-2 w-full mb-4 border rounded">
            
            <label for="userPhoto" class="block font-bold mb-2">大頭照:</label>
            <img id="userPhotoImage" src="${userData.photo || 'default_image_path_here.jpg'}" alt="${userData.name}" class="mb-4" width="200">
            <input type="file" id="userPhoto" name="userPhoto" class="mb-4">
    
            <button onclick="updateUserDetails()" class="px-4 py-2 font-bold text-white bg-blue-500 w-full rounded-full hover:bg-blue-600">更新資料</button>

            <button onclick="returnIndex()" class="px-4 py-2 font-bold text-white bg-red-500 w-full rounded-full hover:bg-blue-600">返回主頁</button>
        `;
    
        userDiv.appendChild(userDetailsContainer);
        userProfile.appendChild(userDiv);
    }

    function updateUserDetails() {
        const apiURL = 'http://localhost:8000/api/user';  // 請注意路徑可能需要修改
    
        const formData = new FormData();
    
        // 添加普通文本字段到 FormData
        formData.append('name', document.getElementById('userName').value);
        formData.append('email', document.getElementById('userEmail').value);
    
        // 添加檔案到 FormData
        const userPhotoFile = document.getElementById('userPhoto').files[0];
        // console.log(userPhotoFile);
        if (userPhotoFile) {
            formData.append('userPhoto', userPhotoFile);
        }
       
        const token = localStorage.getItem('jwtToken');
        fetch(apiURL, {
            method: 'POST',  // 或者 'PUT'，取決於你的API
            headers: {
                // 'Accept': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // 處理返回的資料，例如更新畫面或提示使用者
            document.getElementById('pokemonContainer').style.display = 'block';
            document.getElementById('userProfile').style.display = 'none';
            alert('你已新增成功');


        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    
    // function updateUserDetails() {
    //     const apiURL = 'http://localhost:8000/api/user';  // 請注意路徑可能需要修改
    
    //     // 使用 JavaScript 原生物件來創建請求主體
    //     const requestBody = {
    //         name: document.getElementById('userName').value,
    //         email: document.getElementById('userEmail').value
    //     };
    
    //     const token = localStorage.getItem('jwtToken');
    //     fetch(apiURL, {
    //         method: 'PATCH',  // 或者 'PUT'，取決於你的API
    //         headers: {
    //             'Accept': 'application/json',
    //             'Content-Type': 'application/json', // 設定內容類型為 JSON
    //             'Authorization': 'Bearer ' + token
    //         },
    //         body: JSON.stringify(requestBody) // 把 JavaScript 物件轉換為 JSON 字串
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         // 處理返回的資料，例如更新畫面或提示使用者
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //     });
    // }
    

