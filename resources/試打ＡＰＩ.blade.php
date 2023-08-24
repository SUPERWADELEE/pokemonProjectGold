<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <p class="bg-white"></p>
  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto grid max-w-7xl gap-x-8 gap-y-20 px-6 lg:px-8 xl:grid-cols-3">
      <div class="max-w-2xl">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">寶可夢清單</h2>
        <p class="mt-6 text-lg leading-8 text-gray-600">我家的寶可夢,會後空翻</p>



      </div>
      <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
        <li>
          <div class="flex items-center gap-x-6">
            <img class="h-16 w-16 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
            <div>

              <h3 class="text-base font-semibold leading-7 tracking-tight text-gray-900">Leslie Alexander</h3>
              <p class="text-sm font-semibold leading-6 text-indigo-600">Co-Founder / CEO</p>
              <!-- 新增按鈕 -->
              <button onclick="addPokemon()">登入系統</button>

              <!-- 顯示詳情按鈕 -->
              <button onclick="showPokemonDetails()">顯示詳情</button>
            </div>
          </div>



        </li>

        <!-- More people... -->
      </ul>
    </div>
  </div>

  <script>
    function addPokemon() {
      // 這裡是模擬新增寶可夢的API請求
      const pokemonData = {
      
        email: 'elvis122545735@gmail.com',
        password:'790510'
      };

      fetch('https://wade.monster/api/v1/auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept':'application/json'
          },
          body: JSON.stringify(pokemonData)
        })
        .then(response => response.json())
        .then(data => {
          console.log('Success:', data);
        })
        .catch((error) => {
          console.error('Error:', error);
        });
    }

    function showPokemonDetails() {
      // 這裡是模擬獲取寶可夢詳情的API請求
      fetch('YOUR_API_ENDPOINT_FOR_GETTING_POKEMON_DETAILS')
        .then(response => response.json())
        .then(data => {
          // 這裡你可以顯示寶可夢的詳細資訊
          console.log(data);
        })
        .catch(error => {
          console.error('Error fetching details:', error);
        });
    }
  </script>
</body>

</html>