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
                            <td style="width: 50px">${req.service_date}</td>
                            <td style="width: 50px">${req.service_time}</td>
                            <td style="width: 70px">${req.service_type === 'other' ? 'Иная услуга' : req.service_type}</td>
                            <td style="width: 100px">${req.other_service_text || ''}</td>
                            <td style="width: 70px">${req.address}</td>
                            <td style="width: 50px">${req.phone}</td>
                            <td style="width: 30px">${req.payment_method === 'cash' ? 'Наличные' : 'Банковская карта'}</td>
                            <td style="width: 40px">${req.status}</td>
                            <td style="width: 70px">${req.cancel_reason}</td>
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