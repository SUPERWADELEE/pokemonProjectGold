function createOrder() {
    // 3. 創建和發送請求
    const token = localStorage.getItem('jwtToken');
    const totalPrice = parseInt(localStorage.getItem('totalPrice'), 10);

    console.log(totalPrice);
    fetch('http://localhost:8000/api/orders', {
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
        })

    // .then(createOrderdetails())
}


// function createOrderdetails(){
//     // 3. 創建和發送請求
//   const token = localStorage.getItem('jwtToken');
//   const totalPrice = localStorage.getItem('totalPrice'); 
//   fetch('http://localhost:8000/api/orders', {
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json',
//       'Authorization': 'Bearer ' + token, // 這裡添加token
//       'Accept': 'application/json'
//     },
//     body: JSON.stringify({
//         order_id: ,
//         race_id: ,
//         quantity: "paid"
//       })
//   })
//   .then(response => {
//     if (!response.ok) {
//       throw new Error('Network response was not ok');
//     }})

//     .then(createOrderdetails())
// } 
