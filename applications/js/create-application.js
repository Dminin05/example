const form = document.getElementById('service-request-form');
const resultDiv = document.getElementById('result');
const otherServiceCheck = document.getElementById('other-service-check');
const otherServiceContainer = document.getElementById('other-service-container');

otherServiceCheck.addEventListener('change', () => {
    if (otherServiceCheck.checked) {
        otherServiceContainer.classList.remove('hidden');
        document.getElementById('other-service-text').required = true;
    } else {
        otherServiceContainer.classList.add('hidden');
        document.getElementById('other-service-text').required = false;
    }
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    resultDiv.textContent = '';

    const phoneRegex = /^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/;
    const phone = form.phone.value.trim();

    if (!phoneRegex.test(phone)) {
        resultDiv.textContent = 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX';
        return;
    }

    const formData = {
        address: form.address.value.trim(),
        phone: phone,
        date: form.date.value,
        time: form.time.value,
        service: otherServiceCheck.checked ? 'other' : form.service.value,
        otherServiceText: otherServiceCheck.checked ? form['other_service_text'].value.trim() : '',
        payment: form.payment.value
    };

    // Пример отправки на сервер
    try {
        const response = await fetch('../api/create-application.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include',
            body: JSON.stringify(formData)
        });

        const result = await response.json();

        if (response.ok) {
            resultDiv.textContent = 'Заявка успешно отправлена';
            form.reset();
            otherServiceContainer.classList.add('hidden');
        } else {
            resultDiv.textContent = result.error || 'Ошибка при отправке';
        }
    } catch (err) {
        resultDiv.textContent = 'Ошибка сети: ' + err.message;
    }
});