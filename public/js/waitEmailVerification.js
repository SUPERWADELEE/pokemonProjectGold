 // // otherFile.js
 import { API_DOMAIN } from './config.js';
let verificationInterval;

function showWaitPage(email) {
    // 顯示等待頁面
    document.getElementById('waitPage').style.display = 'block';
    document.getElementById('registerPage').style.display = 'none';


    // 定期檢查驗證狀態 poling
    verificationInterval = setInterval(() => checkVerificationStatus(email), 5000);
}


function checkVerificationStatus(email) {
    fetch(`${API_DOMAIN}/api/checkVerificationStatus/${email}`)
    .then(response => response.json())
    .then(data => {
        if (data.isVerified) {
            clearInterval(verificationInterval);  // 停止定期檢查
            handleLogin();  // 或其他成功驗證後的行為
        }
    })
    .catch(error => {
        console.error('Error checking verification status:', error);
    });
}

function resendVerificationEmail() {
    // 重新發送驗證郵件的代碼...
}

function redirectToLoginPage() {
    clearInterval(verificationInterval);  // 停止定期檢查
    document.getElementById('waitPage').style.display = 'none';
    document.getElementById('loginPage').style.display = 'block';
}

window.showWaitPage = showWaitPage;
