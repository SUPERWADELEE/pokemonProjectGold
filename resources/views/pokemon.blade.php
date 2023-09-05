<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100"> <!-- Add classes here to center the content vertically and apply a background -->
    <div class="bg-white p-8 rounded-lg shadow-md text-center"> <!-- Add a wrapper div for styling -->
        <figure class="mt-10">
            <blockquote class="text-center text-xl font-semibold leading-8 text-gray-900 sm:text-2xl sm:leading-9">
            </blockquote>
            <figcaption class="mt-10">
                <h1 id="pokemonName"></h1>
                <img id="pokemonImage" class="w-64 h-64 rounded-full" src="" alt="Pokemon Image">
                <p id="pokemonLevel"></p>
                <p id="pokemonNature"></p>
                <p id="pokemonAbility"></p>
                <p id="pokemonRace"></p>
                <h2 id="pokemonSkillsTitle">技能:</h2>
                <ul id="pokemonSkills"></ul>



                <div class="text-gray-600">CEO of Workcation</div>
                <button onclick="upgradePokemon()">修改</button>
                <button onclick="deletePokemon()">放生</button>
                <button onclick="evolutionPokemon()">進化</button>
            </figcaption>
        </figure>
    </div> <!-- Close the wrapper div -->
</body>

</html>

<!-- ... -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 從URL中獲取寶可夢的ID
        const segments = window.location.pathname.split('/');
        const pokemonId = segments[segments.length - 1];

        const token = localStorage.getItem('jwtToken');
        // 如果有ID，則發出API請求
        if (pokemonId) {
            fetch(`http://127.0.0.1:8000/api/pokemons/${pokemonId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                })
                .then(response => response.json())
                .then(responseData => {
                    const data = responseData.data; // Here, we get the data from responseData.data

                    console.log('Success:', data);

                    document.getElementById('pokemonName').textContent = data.name;
                    document.getElementById('pokemonImage').src = data.photo;
                    document.getElementById('pokemonLevel').textContent = `等級: ${data.level}`;
                    document.getElementById('pokemonNature').textContent = `性格: ${data.nature}`;
                    document.getElementById('pokemonAbility').textContent = `能力: ${data.ability}`;
                    document.getElementById('pokemonRace').textContent = `種族: ${data.race}`;

                    // For skills, loop through the array and append to the list
                    // ... 其他的代碼 ...

                    // For skills, loop through the array and append to the list
                    if (data.skills && Array.isArray(data.skills)) {
                        const skillsList = document.getElementById('pokemonSkills');
                        skillsList.innerHTML = ''; // Clear any previous skills

                        data.skills.forEach(skill => {
                            const listItem = document.createElement('li');
                            listItem.textContent = skill;
                            skillsList.appendChild(listItem);
                        });
                    }

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    });
</script>


</body>

</html>