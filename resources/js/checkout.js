function checkout(totalPrice){
// 3. 創建和發送請求
const dataToSend = {
    totalPrice: totalPrice
};
const token = localStorage.getItem('jwtToken');
fetch('http://localhost:8000/api/payments', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token ,// 這裡添加token
      'Accept': 'application/json'
    },

    body: JSON.stringify(dataToSend) 
  })

  .then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})

.then(data => {
  // 創建一個表單元素
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = data.payment_url;

  // 添加可見的輸入字段到表單
  const fieldInfo = [
    { name: 'MerchantID', value: data.mid, label: 'MID' },
    { name: 'Version', value: '2.0', label: 'Version' },
    { name: 'TradeInfo', value: data.edata1, label: 'TradeInfo' },
    { name: 'TradeSha', value: data.hash, label: 'TradeSha' }
  ];

  // 將上述陣列中的每一個物件都創建input
 fieldInfo.forEach(field => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = field.name;
    input.value = field.value;
    input.readOnly = true;

    // 只添加 input 到表單，移除了 label 和 br 的创建和附加
    form.appendChild(input);
});


// 創建submit到form表單裡
  const submitButton = document.createElement('input');
  submitButton.type = 'submit';
  form.appendChild(submitButton);

  // 將表單附加到body
  document.body.appendChild(form);

  // 使用 JavaScript 自動提交表單
  form.submit();
})


}