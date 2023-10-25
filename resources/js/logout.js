function logout() {
  const token = localStorage.getItem('jwtToken');
  fetch('https://wade.monster/api/Auth/logout', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token ,
        'Accept': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Logout failed');
      }
      return response.json();
    })
    .then(data => {
      console.log('Logged out successfully');
      localStorage.removeItem('jwtToken');  // 确保在确认登出成功后删除token
      showLoginPage();
      
      // 强制重新加载页面  
      // window.location.reload(true);
      
    })
    .catch(error => {
      console.error('Logout error:', error);
    });
}
