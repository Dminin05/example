document.addEventListener("DOMContentLoaded", function () {
    const result = document.getElementById("result");
    localStorage.setItem("isLoggedIn", "false");
    localStorage.setItem("userEmail", undefined); 

    document.getElementById("login").addEventListener("click", async () => {
        const email = document.getElementById("login-email").value.trim();
        const password = document.getElementById("login-password").value;

        result.textContent = ""; // Очистить старое сообщение

        // === Валидация полей ===
        if (!email || !password) {
            result.textContent = "Пожалуйста, заполните все поля.";
            return;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            result.textContent = "Введите корректный email.";
            return;
        }

        try {
            const response = await fetch("../api/login.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                // Сохраняем флаг авторизации
                localStorage.setItem("isLoggedIn", "true");
                localStorage.setItem("userEmail", email);

                // Перенаправляем на защищённую страницу
                window.location.replace("../index.html");
            } else {
                result.textContent = data.error;
            }

        } catch (error) {
            result.textContent = "Сетевая ошибка: " + error.message;
        }
    });
});