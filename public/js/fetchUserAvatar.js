 // // otherFile.js
 import { API_DOMAIN } from './config.js';
 
 async function fetchUserAvatar() {
    const token = localStorage.getItem('jwtToken');
    
    try {
        const response = await fetch(`${API_DOMAIN}/api/user`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const data = await response.json();
        const avatarUrl = data.photo;
        document.getElementById('avatar').src = avatarUrl;
    } catch (error) {
        console.error('There has been a problem with your fetch operation:', error);
    }
}


// 当页面加载时调用函数
// window.onload = fetchUserAvatar;
window.fetchUserAvatar = fetchUserAvatar;
