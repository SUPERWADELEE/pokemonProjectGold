async function fetchUserAvatar() {
    const token = localStorage.getItem('jwtToken');
    try {
        const response = await fetch('https://wade.monster/api/user', {
            method: 'GET', // 或者其他HTTP方法，如 'POST'，如果需要
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token // 這裡添加token
                // 如果需要，添加其他头，例如身份验证令牌
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const data = await response.json();
        const avatarUrl = data.photo;  // 根据你的API响应结构调整这一行
        document.getElementById('avatar').src = avatarUrl;
    } catch (error) {
        console.error('There has been a problem with your fetch operation:', error);
    }
}

// 当页面加载时调用函数
window.onload = fetchUserAvatar;
