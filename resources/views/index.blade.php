<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send POST Request</title>
</head>
<body>

<form id="pokemonForm">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="smallblueBirdd" required>

    <label for="race_id">Race ID:</label>
    <input type="number" id="race_id" name="race_id" value="1" required>

    <label for="skills">Skills:</label>
    <input type="number" name="skills[]" value="13" required>
    <input type="number" name="skills[]" value="14" required>
    <input type="number" name="skills[]" value="15" required>

    <label for="ability_id">Ability ID:</label>
    <input type="number" id="ability_id" name="ability_id" value="2" required>

    <label for="nature_id">Nature ID:</label>
    <input type="number" id="nature_id" name="nature_id" value="1" required>

    <label for="level">Level:</label>
    <input type="number" id="level" name="level" value="100" required>

    <button type="button" onclick="submitForm()">Submit</button>
</form>

<script>
    

    function submitForm() {
    const apiToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL0F1dGgvbG9naW4iLCJpYXQiOjE2OTY2NTM2MDcsImV4cCI6MTY5NjY1NzIwNywibmJmIjoxNjk2NjUzNjA3LCJqdGkiOiI4d3plT01lTnBueG1pY0M2Iiwic3ViIjoiNCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.1Y9oqMHGrwglxE-biMovVfi27ECP40inEs_vxRLDgic';  // 請將此值更改為您的API token
    const formData = new FormData(document.getElementById('pokemonForm'));
    
    fetch('/api/pokemons', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${apiToken}`,
            'Accept': 'text/html',  // 期待回應是HTML格式
        },
        body: formData
    })
    .then(response => response.text())  // 轉換回應為純文字
    .then(html => {
        document.documentElement.innerHTML = html;  // 設置整個頁面的HTML內容
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Check the console for details.');
    });
}

</script>

</body>
</html>
