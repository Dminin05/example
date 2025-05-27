const emailInput = document.getElementById('email');
const storedEmail = localStorage.getItem('userEmail');
if (storedEmail) {
    emailInput.value = storedEmail;
} else {
    emailInput.value = '';
}

const isLoggedIn = localStorage.getItem("isLoggedIn");
if (!isLoggedIn || isLoggedIn !== "true") {
    // Перенаправляем на страницу входа
    window.location.replace("../pages/login.html");
}

document.getElementById('feedback-form').addEventListener('submit', async function (e) {

    e.preventDefault();
    const email = emailInput.value;
    const message = document.getElementById('message').value;

    const formData = new FormData();
    formData.append('email', email);
    formData.append('message', message);

    const response = await fetch('../api/create-feedback.php', {
        method: 'POST',
        body: formData,
    });

    const result = await response.text();
    alert(result);
    this.reset();
    window.location.replace("../index.html");
    emailInput.value = storedEmail;
});