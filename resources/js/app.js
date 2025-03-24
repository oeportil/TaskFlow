import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;




Alpine.start();


function openModal(proyectoId) {
    // Actualizar la URL del formulario con el ID del proyecto
    const form = document.getElementById('deleteForm');
    form.action = '/proyecto/' + proyectoId + '/delete';

    // Mostrar el modal
    document.getElementById('confirmationModal').classList.remove('hidden');
}

function closeModal() {
    // Ocultar el modal
    document.getElementById('confirmationModal').classList.add('hidden');
}
