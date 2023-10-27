 // // otherFile.js
 import { API_DOMAIN } from './config.js';
function ordersIndex(){
    document.getElementById('pokemon-detail').style.display = 'none';
    document.getElementById('pokemonDetail').style.display = 'none';
    document.getElementById('pokemonList').style.display = 'none';
    document.getElementById('ordersIndex').style.display = 'block';
    document.getElementById('orderDetails').style.display = 'none';
    document.getElementById('pagination').style.display = 'none';  
    const token = localStorage.getItem('jwtToken');

  fetch(`${API_DOMAIN}/api/orders/`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + token // 這裡添加token
    }
  })
  .then(response => response.json())
  .then(data=>{
    showOrder(data.data);
    console.log('Success');
  })
  .catch((error) => {
    console.error('Error:', error);
  });
}

function showOrder(orders){
    const ordersList = document.getElementById('ordersIndex');
  ordersList.innerHTML = ''; // 清空列表
  orders.forEach(order => {
    const listItem = document.createElement('li');
    listItem.classList.add('bg-white', 'rounded-lg', 'shadow', 'p-4', 'flex', 'flex-col', 'items-center');

    listItem.innerHTML = `
        <img class="h-48 w-48 rounded-full mb-4 pokemon-image" data-id="${order.id}" src="/order.png">
        <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">${order.user_name}</h3>
        <p class="text-sm text-gray-600">訂單總價格: ${order.total_price}</p>
        <p class="text-sm text-gray-600">結帳方式: ${order.payment_method}</p>
        <p class="text-sm text-gray-600">結帳狀態: ${order.payment_status}</p>
        <button onclick="orderDetailsIndex( ${order.id})" class="mt-6 px-4 py-2 font-bold text-white bg-blue-600 rounded-full hover:bg-blue-700">訂單詳情</button>
        
      `;
      ordersList.appendChild(listItem);
});
}


function orderDetailsIndex(order_id){
  const token = localStorage.getItem('jwtToken');
  fetch(`${API_DOMAIN}/api/orders/${order_id}/order_details`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer ' + token // 這裡添加token
    }
  })

  .then(response => response.json())
  .then(details => {
    // 使用上面定義的函數來更新畫面
    showOrderDetail(details.data);
  })

}


function showOrderDetail(orders){
  document.getElementById('pagination').style.display = 'none';
  document.getElementById('ordersIndex').style.display = 'none';
  document.getElementById('orderDetails').style.display = 'block';  
  const orderDetailsList = document.getElementById('orderDetails');
orderDetailsList.innerHTML = ''; // 清空列表
orders.forEach(orderDetail => {
  const listItem = document.createElement('li');
  listItem.classList.add('bg-white', 'rounded-lg', 'shadow', 'p-4', 'flex', 'flex-col', 'items-center');

  listItem.innerHTML = `
      <img class="h-48 w-48 rounded-full mb-4 pokemon-image" data-id="${orderDetail.id}" src="/orderDetail.png">
      <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">${orderDetail.race_name}</h3>
      <p class="text-sm text-gray-600">結帳數量: ${orderDetail.quantity}</p>
      <p class="text-sm text-gray-600">結帳方式: ${orderDetail.unit_price}</p>
      <p class="text-sm text-gray-600">結帳狀態: ${orderDetail.subtotal_prcie}</p>
      
      
    `;
    orderDetailsList.appendChild(listItem);
});
}
window.ordersIndex = ordersIndex;
window.orderDetailsIndex = orderDetailsIndex;