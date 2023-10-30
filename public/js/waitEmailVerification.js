 // // otherFile.js
 import { API_DOMAIN } from './config.js';
let verificationInterval;

function showWaitPage(email) {
    // 顯示等待頁面
    document.getElementById('waitPage').style.display = 'block';
    document.getElementById('registerPage').style.display = 'none';


    // checkVerificationStatus(email)


    // // 定期檢查驗證狀態 poling
    // verificationInterval = setInterval(() => checkVerificationStatus(email), 10000);
    checkVerificationStatus(email, true);  // 第二個參數表示這是第一次請求


}


function checkVerificationStatus(email, initialRequest = false) {
    
    fetch(`${API_DOMAIN}/api/checkVerificationStatus/${email}`)
    .then(response => response.json())
    .then(data => {
        if (data.isVerified) {
            clearInterval(verificationInterval);  // 停止定期檢查
            handleLogin();  // 或其他成功驗證後的行為
        } else {
            if (initialRequest) {
                // 若為第一次請求，則開始定期檢查
                verificationInterval = setInterval(() => checkVerificationStatus(email), 10000);
            }
            if (!data.isVerified) {
                // 如果輪詢超時並返回 false（或其他您選擇的標誌）
                alert("你尚未驗證註冊信。請檢查您的電子郵件並按下確認連結。");
            }
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
