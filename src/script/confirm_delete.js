// petit script pour confirmer une suppression + actualisation de la page automatique si
function confirmerSuppression(id) {
    if (confirm('Voulez-vous vraiment supprimer cette réservation ?')) {
        window.location.href = '?supprimer=' + id;
    }
}   