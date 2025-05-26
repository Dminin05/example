fetch('../admin/api/get-orders.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector('#orders-table tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4">У вас пока нет заказов</td></tr>';
            return;
        }

        const statuses = ["Создан", "Оплачен", "Готов к выдаче"];

        data.forEach(order => {
            const tr = document.createElement('tr');

            const date = new Date(order.created_at);
            const formattedDate = date.toLocaleString('ru-RU', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            const statusOptions = statuses.map(status => `
                <option value="${status}" ${order.status === status ? 'selected' : ''}>${status}</option>
            `).join('');

            tr.innerHTML = `
                <td>#${order.id}</td>
                <td>${formattedDate}</td>
                <td>
                    <select class="status-select" data-order-id="${order.id}">
                        ${statusOptions}
                    </select>
                </td>
                <td>${order.total_price} ₽</td>
            `;

            tbody.appendChild(tr);
        });

        // Вешаем один обработчик на все select после их создания
        tbody.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', event => {
                const newStatus = event.target.value;
                const orderId = event.target.dataset.orderId;

                console.log(`Изменение статуса заказа #${orderId} на "${newStatus}"`);

                fetch('../admin/api/update-order-status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: orderId,
                        status: newStatus
                    })
                })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Ответ сервера:', result);
                    })
                    .catch(error => {
                        console.error('Ошибка при обновлении статуса:', error);
                    });
            });
        });
    })
    .catch(err => {
        document.querySelector('#orders-table tbody').innerHTML =
            '<tr><td colspan="4">Ошибка при загрузке заказов</td></tr>';
    });