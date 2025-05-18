document.addEventListener("DOMContentLoaded", function () {
    const result = document.getElementById("result");
    localStorage.setItem("isLoggedIn", "false");
    localStorage.setItem("userEmail", undefined); 

    document.getElementById("register").addEventListener("click", async () => {
        const first_name = document.getElementById("reg-first-name").value.trim();
        const last_name = document.getElementById("reg-last-name").value.trim();
        const email = document.getElementById("reg-email").value.trim();
        const password = document.getElementById("reg-password").value;

        const resultDiv = document.getElementById("result");
        resultDiv.textContent = ""; // очистим старые сообщения

        // === Простая клиентская валидация ===
        if (!first_name || !last_name || !email || !password) {
            resultDiv.textContent = "Пожалуйста, заполните все поля.";
            return;
        }

        // Проверка email с помощью регулярного выражения
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            resultDiv.textContent = "Некорректный email.";
            return;
        }

        if (password.length < 6) {
            resultDiv.textContent = "Пароль должен содержать минимум 6 символов.";
            return;
        }

        // === Отправка запроса ===
        try {
            const response = await fetch("../api/register.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ first_name, last_name, email, password })
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.setItem("isLoggedIn", "true");
                localStorage.setItem("userEmail", email);
                window.location.replace("../index.html");
            } else {
                resultDiv.textContent = data.error || "Ошибка регистрации.";
            }
        } catch (err) {
            resultDiv.textContent = "Сетевая ошибка: " + err.message;
        }
    });
});