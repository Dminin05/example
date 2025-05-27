const form = document.getElementById('registration-form');
const resultDiv = document.getElementById('result');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    resultDiv.textContent = '';
    resultDiv.style.color = 'red';

    const formData = {
        fio: form.fio.value.trim(),
        phone: form.phone.value.trim(),
        email: form.email.value.trim(),
        login: form.login.value.trim(),
        password: form.password.value.trim()
    };

    const fioRegex = /^[А-Яа-яЁё\s]+$/;
    const phoneRegex = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (formData.patronymic && !fioRegex.test(formData.patronymic)) {
        resultDiv.textContent = 'Поле "Отчество" должно содержать только кириллицу и пробелы';
        return;
    }

    if (!phoneRegex.test(formData.phone)) {
        resultDiv.textContent = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX';
        return;
    }

    if (!emailRegex.test(formData.email)) {
        resultDiv.textContent = 'Некорректный формат электронной почты';
        return;
    }

    if (formData.password.length < 6) {
        resultDiv.textContent = 'Пароль должен содержать минимум 6 символов';
        return;
    }

    resultDiv.textContent = '';

    try {
        const response = await fetch('../api/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            const data = await response.json();
            resultDiv.style.color = 'green';
            resultDiv.textContent = 'Регистрация прошла успешно!';
            window.location.href = '../index.html';
        } else {
            const error = await response.json();
            resultDiv.textContent = error.message || 'Ошибка регистрации';
        }
    } catch (err) {
        console.error(err);
        resultDiv.textContent = 'Ошибка подключения к серверу';
    }
});