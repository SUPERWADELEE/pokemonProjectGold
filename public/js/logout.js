function logout() {
    // 調用API的登出端點
    const token = localStorage.getItem('jwtToken');
    fetch('http://localhost:8000/api/Auth/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer ' + token ,
          'Accept': 'application/json',// 這裡添加token
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
        showLoginPage()
      })
      .catch(error => {
        console.error('Logout error:', error);
      });
    localStorage.removeItem('jwtToken');




  }