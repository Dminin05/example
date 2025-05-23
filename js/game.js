// Получаем ID из URL
const urlParams = new URLSearchParams(window.location.search);
const gameId = urlParams.get('id');

const container = document.getElementById("game-container");

fetch('../../api/get-goods.php') // путь к твоему PHP-файлу
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err });
        }

        return response.json();
    })
    .then(data => {
        const game = data.find(product => product.id == gameId);
        container.innerHTML = `
            <img class="game-cover" src="../images/${game.image}" alt="${game.title}">
            <div class="game-info">
                <h1 class="game-title">${game.title}</h1>
                <p class="game-price">${game.price}</p>
                <p class="game-description">${game.description}</p>
                <button class="in-cart-button">В корзину</button>
            </div>
        `;
        const formData = new FormData();
        formData.append('good_id', game.id);
        document.querySelector(".in-cart-button").addEventListener("click", async () => {
            const response = await fetch('../api/add-to-cart.php', {
                method: 'POST',
                body: formData,
            });

            if (response.status == 401) {
                window.location.replace("login.html");
            }
        });
    })

