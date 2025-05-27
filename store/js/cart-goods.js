fetch('../api/get-goods-in-cart.php')
  .then(response => {
    if (!response.ok) {
      return response.json().then(err => { throw err });
    }

    console.log(response)

    if (response.status == 401) {
      window.location.replace("login.html");
    }
    return response.json();
  })
  .then(data => {
    const container = document.getElementById('cart-items'); // контейнер для карточек
    let html = '';
    let sum = 0

    data.forEach(product => {
      sum += product.price * product.quantity
      html += renderCartItem(product);
    });

    container.innerHTML = html;
    document.getElementById('total-price').textContent = sum

    // После вставки можно повесить обработчики на кнопки, если нужно
    // Например:
    // document.querySelectorAll('.increase').forEach(btn => btn.addEventListener('click', ...));
  })
  .catch(err => {
    console.error('Ошибка загрузки:', err);
  });

function renderCartItem(item) {
  return `
    <div class="cart-item">
      <img src="../images/${item.image}" alt="${item.title}">
      <div class="item-details">
        <p class="item-title">${item.title}</p>
        <p class="item-price">${item.price} ₽</p>
        <div class="quantity-controls">
          <button class="decrease" data-id="${item.id}">−</button>
          <span>${item.quantity}</span>
          <button class="increase" data-id="${item.id}">+</button>
        </div>
      </div>
    </div>
  `;
}

document.querySelector('.checkout-button').addEventListener('click', () => {

  fetch('../api/get-goods-in-cart.php')
    .then(response => {
      if (!response.ok) {
        return response.json().then(err => { throw err });
      }

      console.log(response)

      if (response.status == 401) {
        window.location.replace("login.html");
      }
      return response.json();
    })
    .then(data => {

      let sum = 0

      data.forEach(product => {
        sum += product.price * product.quantity
      });

      fetch('../api/post-order.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          items: data, // массив товаров из корзины
          totalPrice: sum
        })
      })
        .then(response => {
          if (!response.ok) {
            return response.json().then(err => { throw err });
          }
          return response.json();
        })
        .then(result => {
          alert('Заказ успешно оформлен! Номер заказа: ' + result.orderId);
          window.location.href = "../index.html"; // перенаправление
        })
        .catch(err => {
          console.error('Ошибка при оформлении заказа:', err);
          alert('Ошибка при оформлении заказа');
        });
    })


});