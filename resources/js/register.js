function handleRegister() {
    // 隱藏登錄界面
    document.getElementById('loginPage').style.display = 'none';

    // 顯示寶可夢的界面
    document.getElementById('registerPage').style.display = 'block';
    const name = document.getElementById('nameInput').value;
    const email = document.getElementById('register_emailInput').value;
    const password = document.getElementById('register_passwordInput').value;
    const password_confirmation = document.getElementById('register_confirm_passwordInput').value;
    register(name,email, password, password_confirmation)
    .then(data => {
        // 這裡您可以使用返回的數據，如 data.token 等
        // 假設您只想傳遞 email
        // console.log('fuck');
        showWaitPage(email);
      })
  }

  function register(name,email, password, password_confirmation) {
    // console.log('fuck');
    return fetch('http://localhost:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          name: name,
          email: email,
          password: password,
          password_confirmation: password_confirmation
        })
      })
      .then(response => {
        if (response.ok) {
          return response.json();
        } else {
          return response.json().then(data => {
            throw new Error(data.message || 'Unable to login');
          });
        }
      })
      
  }