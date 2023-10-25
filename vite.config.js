import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/register.js',
                'resources/js/fetchUserAvatar.js',
                'resources/js/userProfile.js',
                'resources/js/ordersIndex.js',
                'resources/js/showPurchasedPokemon.js',
                'resources/js/checkout.js',
                'resources/js/login.js',
                'resources/js/logout.js',
                'resources/js/waitEmailVerification.js',
                'resources/js/showPage.js',
                'resources/js/shoppingCart.js',
                'resources/js/togglePagination.js',
                'resources/js/pokemonsIndex.js',
                'resources/js/racesIndex.js',
                'resources/js/fetchAndPopulateDropdown.js',
                'resources/js/fetchAndPopulateDropdownSkills.js',
                'resources/js/populateEvolutionDropdown.js',
                'resources/js/updatePokemonDetail.js',
                'resources/js/createPokemons.js',],
            refresh: true,
        }),
    ],
});
