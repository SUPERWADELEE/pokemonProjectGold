







function showPurchasedPokemon(){
    document.getElementById('loginPage').style.display = 'none';
    // 由使用者點選購物車後觸發function fetchShoppingCart() {
    // 假設API的URL是 'https://your-backend.com/api/cart'
    const apiURL = 'http://localhost:8000/api/cart_items';
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
        .then(data => {
            // 在此處處理數據，例如渲染到網頁中
            updatePokemonDetails(data.data);
        })
        .catch(error => {
            // 處理任何錯誤，例如顯示給用戶看
            console.error('There was a problem fetching the cart data:', error);
        });

    // function displayCartData(cartData) {
    //   // 使用DOM操作或使用你的前端框架來渲染購物車數據
    //   // 例如: 更新商品列表、價格等...
    // }

}
