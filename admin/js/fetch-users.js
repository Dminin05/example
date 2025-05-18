fetch('../../api/get-users.php') // путь к твоему PHP-файлу
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err });
        }

        return response.json();
    })
    .then(data => {
        const tbody = document.querySelector('#users tbody');

        data.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.first_name}</td>
                <td>${user.last_name}</td>
                <td>${user.email}</td>
                <td>${user.role || '-'}</td>
            `;
            tbody.appendChild(row);
        });
    })
    .catch(error => {
        document.getElementById('error').textContent = error.error || 'Неизвестная ошибка';
    });