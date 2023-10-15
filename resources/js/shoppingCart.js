// 由使用者點選購物車後觸發
function fetchShoppingCart() {
    // 假設API的URL是 'https://your-backend.com/api/cart'
    const apiUrl = 'http://localhost:8000/api/cart_items';
    const token = localStorage.getItem('jwtToken');
    fetch(apiUrl, {
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
            displayShoppingCart(data);
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




function displayShoppingCart(cartData) {
    const shoppingCart = document.getElementById('shoppingCart');
    document.getElementById("pokemonContainer").style.display = 'none';
    document.getElementById("pagination").style.display = 'none';

    let totalAmount = 0;

    // 動態生成購物車商品項目的 HTML
    const cartItems = cartData.data.map(item => {
        totalAmount += parseFloat(item.current_price) * item.amount;

        return `
            <div class="cart-item flex items-center justify-between border-b pb-4 mb-4">
                <input type="checkbox" class="mr-4 form-checkbox h-5 w-5 text-blue-600 cart-item-checkbox" checked onchange="updateTotal()">

                <img src="${item.race_photo}" alt="${item.race_name}" class="w-24 h-24 object-cover rounded-lg mr-4">

                <div class="cart-item-details flex-grow">
                    <span class="cart-item-title block text-lg font-semibold text-gray-800 mb-2">${item.race_name}</span>
                    <span class="cart-item-price text-gray-800">NT$ ${item.current_price}</span>
                </div>

                <div class="cart-item-quantity w-20 mr-4">
                    <select class="w-full border rounded p-1 quantity-select" data-price="${item.current_price}" onchange="updateTotal()">
                        ${Array.from({ length: 10 }, (_, i) => i + 1).map(num =>
                            num === item.amount
                                ? `<option value="${num}" selected>${num}</option>`
                                : `<option value="${num}">${num}</option>`
                        ).join('')}
                    </select>
                </div>

                <button class="text-red-500 hover:text-red-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }).join('');

    // 購物車的整體 HTML 內容
    const cartContent = `
    <div class="cart bg-white p-8 shadow-md rounded-lg w-full max-w-2xl mx-auto mt-12">
        ${cartItems}
        <div class="cart-summary">
            <div class="cart-total flex justify-between mb-4">
                <span class="text-lg font-semibold text-gray-800">總計</span>
                <span class="text-lg font-semibold text-gray-800" id="totalAmount">NT$ ${totalAmount.toFixed(2)}</span>
            </div>
            <button onclick="checkout(${totalAmount.toFixed(2)})" class="checkout-btn w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-300">前往結帳</button>
            <!-- 新增的返回首頁按鈕 -->
            <button onclick="returnIndex()" class="return-btn w-full bg-gray-500 text-white py-2 rounded-lg hover:bg-gray-600 transition duration-300 mt-4">返回首頁</button>
        </div>
    </div>
    `;

    localStorage.setItem('totalPrice', totalAmount.toFixed(2));
    // 將購物車的 HTML 內容添加到 shoppingCart 標籤的位置
    shoppingCart.innerHTML = cartContent;
    // 在購物車渲染完畢後，初始化總計
    updateTotal();
}



function updateTotal() {
    const selectedItems = document.querySelectorAll('.cart-item input[type="checkbox"]:checked');
    let total = 0;
    selectedItems.forEach(item => {
        const quantitySelect = item.closest('.cart-item').querySelector('.quantity-select');
        const quantity = parseInt(quantitySelect.value, 10);
        const price = parseFloat(quantitySelect.getAttribute('data-price'));
        total += quantity * price;
    });
    document.getElementById('totalAmount').textContent = `NT$ ${total.toFixed(2)}`;
}
