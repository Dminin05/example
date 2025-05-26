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
