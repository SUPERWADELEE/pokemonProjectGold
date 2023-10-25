function userProfile(){
    document.getElementById('pokemonContainer').style.display = 'none';
    document.getElementById('userProfile').style.display = 'block';
    document.getElementById('ordersIndex').style.display = 'none';
    document.getElementById('pagination').style.display = 'none';
    const apiURL = 'https://wade.monster/api/user';
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
        const userPhotoInput = userDetailsContainer.querySelector('#userPhoto');
        const userPhotoImage = userDetailsContainer.querySelector('#userPhotoImage');
        
        userPhotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    userPhotoImage.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        userDiv.appendChild(userDetailsContainer);
        userProfile.appendChild(userDiv);
    }

    function updateUserDetails() {
        const apiURL = 'https://wade.monster/api/user';  // 請注意路徑可能需要修改
    
        const formData = new FormData();
    
        // 添加普通文本字段到 FormData
        formData.append('name', document.getElementById('userName').value);
        formData.append('email', document.getElementById('userEmail').value);
    
        // 添加檔案到 FormData
        const userPhotoFile = document.getElementById('userPhoto').files[0];
        if (userPhotoFile) {
            formData.append('userPhoto', userPhotoFile);
        }
    
        const token = localStorage.getItem('jwtToken');

        fetch(apiURL, {
            method: 'POST',  // 或者 'PUT'，取決於你的API
            headers: {
                'Authorization': 'Bearer ' + token
            },
            body: formData
        })
        .then(response => {
            console.log('Raw response:', response);
            return response.json();
        })
        
        .then(data => {
            console.log(data.presignedUrl);
            if (data.presignedUrl && userPhotoFile) {
                // 使用前端 fetch API 上傳檔案到 AWS S3
                return fetch(data.presignedUrl, {
                    method: 'PUT',
                   
                    body: userPhotoFile
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    alert('您已更新成功');
                    fetchUserAvatar(); 
                    returnIndex();
                    return response.statusText;
                    
                });
            }
        })
        .catch(error => {
            console.log('There was a problem with the fetch operation:', error.message);
        });
    }        
    
  
    

