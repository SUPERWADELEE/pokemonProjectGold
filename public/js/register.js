 // // otherFile.js
 import { API_DOMAIN } from './config.js';
(function(global) {
  // handleRegister function
  function handleRegister() {
      // 隱藏登錄界面
      document.getElementById('loginPage').style.display = 'none';

      // 顯示寶可夢的界面
      document.getElementById('registerPage').style.display = 'block';
      const name = document.getElementById('nameInput').value;
      const email = document.getElementById('register_emailInput').value;
      const password = document.getElementById('register_passwordInput').value;
      const password_confirmation = document.getElementById('register_confirm_passwordInput').value;
      register(name, email, password, password_confirmation)
      .then(data => {
          // 這裡您可以使用返回的數據，如 data.token 等
          // 假設您只想傳遞 email
          showWaitPage(email);
        })
  }

  // register function
  function register(name, email, password, password_confirmation) {
      return fetch(`${API_DOMAIN}/api/register`, {
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

  // Attach your functions to the global object
  global.handleRegister = handleRegister;
  global.register = register;
})(window);
