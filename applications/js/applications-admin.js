async function loadRequests() {
    try {
        const response = await fetch('api/get-admin-applications.php', {
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
                            <td>${req.fio}</td>
                            <td>${req.service_date}</td>
                            <td>${req.service_time}</td>
                            <td>${req.service_type === 'other' ? 'Иная услуга' : req.service_type}</td>
                            <td>${req.other_service_text || ''}</td>
                            <td>${req.address}</td>
                            <td>${req.phone}</td>
                            <td>${req.payment_method === 'cash' ? 'Наличные' : 'Банковская карта'}</td>
                            <td>
                                <select class="status-select" data-id="${req.id}">
                                    <option value="в работе" ${req.status == 'в работе' ? 'selected' : ''}>В работе</option>
                                    <option value="выполнено" ${req.status === 'выполнено' ? 'selected' : ''}>Выполнено</option>
                                    <option value="отменено" ${req.status === 'отменено' ? 'selected' : ''}>Отменено</option>
                                </select>
                                
                            </td>
                            <td> <input class="cancel-reason form-control mt-1" placeholder="Причина отмены"
                                value="${req.cancel_reason || ''}"
                                style="display: ${req.status === 'отменено' ? 'block' : 'none'}" />
                            </td>
                            <td><button class="save">Сохранить</button></td>
                        `;
                tbody.appendChild(tr);

                const select = tr.querySelector('.status-select');
                const reasonInput = tr.querySelector('.cancel-reason');
                const saveBtn = tr.querySelector('.save');

                select.addEventListener('change', () => {
                    reasonInput.style.display = select.value === 'отменено' ? 'block' : 'none';
                });

                saveBtn.addEventListener('click', async () => {
                    const status = select.value;
                    const cancelReason = reasonInput.value.trim();
                    const id = req.id;

                    // 👇 проверка на "отменено"
                    if (status === 'отменено' && !cancelReason) {
                        alert('Укажите причину отмены!');
                        return;
                    }

                    try {
                        const response = await fetch('api/update-status.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            credentials: 'include',
                            body: JSON.stringify({ id, status, cancel_reason: cancelReason })
                        });

                        const result = await response.json();
                        if (response.ok) {
                            alert('Статус обновлён');
                            loadRequests(); // обновление таблицы
                        } else {
                            alert(result.error || 'Ошибка при обновлении');
                        }
                    } catch (err) {
                        alert('Ошибка сети: ' + err.message);
                    }
                });


            });
        } else {
            document.getElementById('result').textContent = result.error || 'Ошибка загрузки';
        }
    } catch (err) {
        document.getElementById('result').textContent = 'Ошибка сети: ' + err.message;
    }
}

function addEventListener(req, tr) {

}

loadRequests();
