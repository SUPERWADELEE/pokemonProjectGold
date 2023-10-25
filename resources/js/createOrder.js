function createOrder() {
    // 3. 創建和發送請求
    const token = localStorage.getItem('jwtToken');
    const totalPrice = parseInt(localStorage.getItem('totalPrice'), 10);

    console.log(totalPrice);
    fetch('https://wade.monster/api/orders', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token, // 這裡添加token
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            total_price: totalPrice,
            payment_method: "credit_card",
            payment_status: "paid"
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();  // 這裡解析響應為JSON
    })
    .then(orderData => {
        const orderId = orderData.id;

        // 使用得到的訂單ID創建訂單細節
        const storedOrderDetails = JSON.parse(localStorage.getItem('orderDetails'));
    
        storedOrderDetails.forEach(race_id => {
            createOrderdetails(orderId, race_id);
        });
        
    });
}

function createOrderdetails(orderId, race_id){
    const token = localStorage.getItem('jwtToken');

    fetch('https://wade.monster/api/orders_details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_id: orderId,
            race_id: race_id,
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
    })
    .then(() => {
        returnIndex();
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error.message);
    });
}

