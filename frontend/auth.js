// auth.js — Gestion de la sécurité des sessions
// À inclure dans toutes les pages protégées

function checkSession(onSuccess) {
    $.ajax({
        url: '../backend/check_session.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (!data.connected) {
                window.location.href = 'index.html';
                return;
            }
            // Afficher le nom de l'utilisateur si présent
            if (data.user_nom) {
                $('#userNom').text(data.user_nom);
                $('#userNomMobile').text(data.user_nom);
            }
            // Afficher/cacher éléments selon le rôle
            if (data.user_role === 'user') {
                $('.admin-only').hide();
                $('.owner-only').hide();
            } else if (data.user_role === 'owner') {
                $('.admin-only').hide();
            }
            // Callback optionnel
            if (typeof onSuccess === 'function') {
                onSuccess(data);
            }
        },
        error: function () {
            window.location.href = 'index.html';
        }
    });
}

// Déconnexion
function logout() {
    $.ajax({
        url: '../backend/logout.php',
        method: 'POST',
        dataType: 'json',
        success: function () {
            window.location.href = 'index.html';
        },
        error: function () {
            window.location.href = 'index.html';
        }
    });
}