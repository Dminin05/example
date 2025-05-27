fetch('/api/user-info.php')
    .then(res => {
        if (!res.ok) throw new Error('Unauthorized');
        return res.json();
    })
    .then(data => {
        document.getElementById('username').textContent = data.name || '—';
        document.getElementById('email').textContent = data.email || '—';
    })
    .catch(err => {
        alert('Необходимо авторизоваться');
        window.location.href = '/login.html';
    });

fetch('../api/get-orders-info.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector('#orders-table tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4">У вас пока нет заказов</td></tr>';
            return;
        }

        data.forEach(order => {
            const tr = document.createElement('tr');

            const date = new Date(order.created_at);
            const formattedDate = date.toLocaleString('ru-RU', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            tr.innerHTML = `
                <td>#${order.id}</td>
                <td>${formattedDate}</td>
                <td>${order.status || '—'}</td>
                <td>${order.total_price} ₽</td>
            `;
            tbody.appendChild(tr);
        });
    })
    .catch(err => {
        document.querySelector('#orders-table tbody').innerHTML =
            '<tr><td colspan="4">Ошибка при загрузке заказов</td></tr>';
    });