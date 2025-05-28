(async function checkAdminRole() {
    try {
        const response = await fetch('../api/get-user-role.php', {
            method: 'GET',
            credentials: 'include'
        });

        const result = await response.json();

        if (!response.ok || result.role === null) {
            window.location.href = 'login.html'; // или на главную
        }
    } catch (err) {
        console.error('Ошибка при проверке роли:', err);
        window.location.href = 'login.html';
    }
})();
