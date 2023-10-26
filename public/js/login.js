 // // otherFile.js
 import { API_DOMAIN } from './config.js';
 
console.log(API_DOMAIN);
function handleLogin() {
 
   // 隱藏登錄界面
   document.getElementById('loginPage').style.display = 'block';
   document.getElementById('waitPage').style.display = 'none';
   // 顯示寶可夢的界面
   document.getElementById('registerPage').style.display = 'none';
    const email = document.getElementById('emailInput').value;
    const password = document.getElementById('passwordInput').value;

    login(email, password)
      .then(token => {
        // 保存token到localStorage
        localStorage.setItem('jwtToken', token);

        // 顯示寶可夢的界面
        showPokemonPage();
      })
      .catch(error => {
        console.error('error', error.message);
      });
  }

  function login(email, password) {
    
    return fetch(`${API_DOMAIN}/api/Auth/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: email,
          password: password
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
      .then(data => {
        return data.token;
      });
  }


  window.handleLogin = handleLogin;