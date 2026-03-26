document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let username = document.getElementById("username").value;
    let pseudo = document.getElementById("pseudo").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    let message = document.getElementById("message");

    if (!username || !password) {
        message.innerHTML = "<span class='text-danger'>Champs obligatoires</span>";
        return;
    }

    if (password !== confirmPassword) {
        message.innerHTML = "<span class='text-danger'>Les mots de passe ne correspondent pas</span>";
        return;
    }

    message.innerHTML = "<span class='text-success'>Prêt à envoyer (API à connecter)</span>";
});