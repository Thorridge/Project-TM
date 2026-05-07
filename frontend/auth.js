// auth.js — Gestion de la sécurité des sessions

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

            // Affichage du nom
            if (data.user_nom) {
                $('#userNom').text(data.user_nom);
                $('#userNomMobile').text(data.user_nom);
            }

            // 🔥 Normalisation du rôle
            const role = (data.user_role || data.role || '').toLowerCase();
            console.log('Rôle détecté :', role, data); // garde ça pour vérifier dans la console

            // USER → ne voit PAS Dashboard ni Mes prêts
            if (role === 'user') {
                $('.nav-dashboard').hide();
                $('.nav-loans').hide();
            }

            // ADMIN + OWNER → voient tout

            if (typeof onSuccess === 'function') {
                onSuccess({ ...data, role });
            }
        },
        error: function () {
            window.location.href = 'index.html';
        }
    });
}

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
