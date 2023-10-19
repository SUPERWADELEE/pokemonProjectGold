function userProfile(){
    document.getElementById('pokemonContainer').style.display = 'none';

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
}