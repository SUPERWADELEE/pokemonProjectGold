function showPokemonPage() {
    // 隱藏登錄界面
    document.getElementById('loginPage').style.display = 'none';

    document.getElementById('registerPage').style.display = 'none';
    document.getElementById('waitPage').style.display = 'none';
    // 顯示寶可夢的界面
    document.getElementById('pokemonContainer').style.display = 'block';
  }



  function showLoginPage() {

    // 顯示登錄界面
    document.getElementById('loginPage').style.display = 'block';

    // 影藏寶可夢的界面
    document.getElementById('pokemonContainer').style.display = 'none';
    

    document.getElementById('registerPage').style.display = 'none';
    document.getElementById('waitPage').style.display = 'none';
    document.getElementById('pagination').style.display = 'none';
    document.getElementById('ordersIndex').style.display = 'none';
    document.getElementById('orderDetails').style.display = 'none';
    
  }
  window.showPokemonPage = showPokemonPage;