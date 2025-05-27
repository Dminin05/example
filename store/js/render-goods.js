const container = document.getElementById("products");

fetch('../api/get-goods.php') // путь к твоему PHP-файлу
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err });
        }

        return response.json();
    })
    .then(data => {
        data.forEach(product => {
            const card = document.createElement("a");
            card.className = "card";
            card.href = `game-window.html?id=${product.id}`;
            card.innerHTML = `
                <img src="../images/${product.image}" alt="${product.title}">
                <div class="card-body">
                    <div class="card-title">${product.title}</div>
                    <div class="card-price">${product.price}</div>
                </div>
            `;
            container.appendChild(card);
        });
    })