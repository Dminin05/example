async function loadRequests() {
    try {
        const response = await fetch('../api/get-applications.php', {
            method: 'GET',
            credentials: 'include'
        });

        const result = await response.json();

        if (response.ok) {
            const tbody = document.querySelector('#requests-table tbody');
            tbody.innerHTML = '';


            if (result.length === 0) {
                document.getElementById('result').textContent = 'У вас пока нет заявок.';
                return;
            }

            result.forEach(req => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                            <td>${req.service_date}</td>
                            <td>${req.service_time}</td>
                            <td>${req.service_type === 'other' ? 'Иная услуга' : req.service_type}</td>
                            <td>${req.other_service_text || ''}</td>
                            <td>${req.address}</td>
                            <td>${req.phone}</td>
                            <td>${req.payment_method === 'cash' ? 'Наличные' : 'Банковская карта'}</td>
                            <td>${req.status}</td>
                            <td>${req.cancel_reason}</td>
                        `;
                tbody.appendChild(tr);
            });
        } else {
            document.getElementById('result').textContent = result.error || 'Ошибка загрузки';
        }
    } catch (err) {
        document.getElementById('result').textContent = 'Ошибка сети: ' + err.message;
    }
}

loadRequests();