function verifierDates() {
    const dateStart = document.getElementById('date-start').value;
    const dateEnd = document.getElementById('date-end').value;

    if (dateStart && dateEnd) {
        // On envoie les dates à get_room.php
        fetch('get_room.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `date_start=${dateStart}&date_end=${dateEnd}`
        })
        .then(response => response.json()) // on convertit la réponse en JSON
        .then(chambres => {
    // On récupère le tbody du tableau
        const tbody = document.getElementById('tbody-chambres');
    
    // On vide le tableau avant de le remplir
            tbody.innerHTML = '';

            // On boucle sur les chambres et on crée une ligne pour chacune
            chambres.forEach(chambre => {
                tbody.innerHTML += `
                    <tr>
                        <td>${chambre.numero}</td>
                        <td>${chambre.type}</td>
                    </tr>
                `;
            });
        });
    }
}