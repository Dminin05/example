document.addEventListener("DOMContentLoaded", function () {
    const button = document.querySelector(".get-users-btn");

    button.addEventListener("click", function () {
        fetch("../api/get-users.php")
            .then(response => response.json())
            .then(users => {
                console.log("Пользователи:", users);
                // пример отображения:
                users.forEach(user => {
                    console.log(`${user.first_name} ${user.last_name} (${user.email})`);
                });
            })
            .catch(error => {
                console.error("Ошибка при запросе:", error);
            });
    });
});