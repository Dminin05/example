const navButtons = document.querySelector(".auth-buttons");
    fetch('api/get-user-role.php')
        .then(res => res.json())
        .then(data => {
            userRole = data.role;

            if (userRole != null) {
                navButtons.innerHTML = `
                <div class="profile-menu">
                    <button id="logout">Выйти</button>
                </div>
            `;

                document.getElementById("logout").addEventListener("click", () => {
                    fetch('api/logout.php', { method: 'POST' })
                        .then(() => {
                            localStorage.clear();
                            window.location.replace("index.html");
                        })
                        .catch((err) => {
                            console.error('Ошибка при выходе:', err);
                        });
                });

              
            } else {
                navButtons.innerHTML = `
                <button onclick="location.href='pages/login.html'">Войти</button>
                <button onclick="location.href='pages/register.html'">Зарегистрироваться</button>
            `;
            }
        })