document.addEventListener("DOMContentLoaded", () => {
    const isLoggedIn = localStorage.getItem("isLoggedIn");
    if (!isLoggedIn || isLoggedIn !== "true") {
        // Перенаправляем на страницу входа
        window.location.replace("../pages/login.html");
    }
});