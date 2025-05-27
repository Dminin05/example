const form = document.getElementById('login-form');
const resultDiv = document.getElementById('result');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    resultDiv.textContent = '';
    resultDiv.style.color = 'red';

    const formData = {
        login: form.login.value.trim(),
        password: form.password.value.trim()
    };

    try {
        const response = await fetch('../api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.ok) {
            resultDiv.style.color = 'green';
            resultDiv.textContent = 'Успешный вход!';
            console.log(data)
            if (data.role === "ADMIN") {
                window.location.href = '../admin.html';
            } else {
                window.location.href = '../index.html';
            }
        } else {
            resultDiv.textContent = data.message || 'Ошибка авторизации';
        }
    } catch (err) {
        console.error(err);
        resultDiv.textContent = 'Ошибка подключения к серверу';
    }
});