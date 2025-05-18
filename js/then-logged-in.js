document.addEventListener("DOMContentLoaded", () => {
    const navButtons = document.querySelector(".auth-buttons");
    const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

    if (isLoggedIn) {
        navButtons.innerHTML = `
                <div class="profile-menu">
                    <button id="profile-toggle">Профиль ▾</button>
                    <div class="dropdown" id="dropdown-menu" style="display: none;">
                        <a href="pages/profile.html">Мой профиль</a>
                        <button id="logout">Выйти</button>
                    </div>
                </div>
            `;

        const toggle = document.getElementById("profile-toggle");
        const menu = document.getElementById("dropdown-menu");
        toggle.addEventListener("click", () => {
            menu.style.display = menu.style.display === "none" ? "block" : "none";
        });

        document.getElementById("logout").addEventListener("click", () => {
            fetch('/api/logout.php', { method: 'POST' })
                .then(() => {
                    localStorage.clear();
                    window.location.replace("../index.html");
                })
                .catch((err) => {
                    console.error('Ошибка при выходе:', err);
                });
        });

        // Закрытие по клику вне меню
        document.addEventListener("click", (e) => {
            if (!navButtons.contains(e.target)) {
                menu.style.display = "none";
            }
        });
    } else {
        navButtons.innerHTML = `
                <button onclick="location.href='pages/login.html'">Войти</button>
                <button onclick="location.href='pages/register.html'">Зарегистрироваться</button>
            `;
    }
});