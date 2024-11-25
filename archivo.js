// Elementos del DOM
const form = document.getElementById('registroForm');
const submitBtn = document.getElementById('submitBtn');
const politicasCheck = document.getElementById('politicasCheck');
const terminosCheck = document.getElementById('terminosCheck');

// Función para validar el formulario completo
function validarFormulario() {
    const todosLosCamposValidos = form.checkValidity();
    const checkboxesAceptados = politicasCheck.checked && terminosCheck.checked;
    submitBtn.disabled = !(todosLosCamposValidos && checkboxesAceptados);
}

// Función para mostrar modal
function mostrarModal(modalId) {
    event.preventDefault();
    document.getElementById(modalId).style.display = "block";
}

// Función para cerrar modal
function cerrarModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Eventos para validar en tiempo real
form.addEventListener('input', validarFormulario);
politicasCheck.addEventListener('change', validarFormulario);
terminosCheck.addEventListener('change', validarFormulario);

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    if (event.target.className === 'modal') {
        event.target.style.display = "none";
    }
}

// Validación de teléfono - solo permitir números
document.querySelector('input[type="tel"]').addEventListener('keypress', function(e) {
    if (e.charCode < 48 || e.charCode > 57) {
        e.preventDefault();
    }
});

// Validación al enviar el formulario
form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Obtener elementos del formulario
    const nombres = form.querySelector('input[placeholder="Nombres"]');
    const apellidos = form.querySelector('input[placeholder="Apellidos"]');
    const email = form.querySelector('input[type="email"]');
    const telefono = form.querySelector('input[type="tel"]');
    const password = form.querySelector('input[type="password"]');
    
    // Validar nombres
    if (!/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/.test(nombres.value)) {
        nombres.setCustomValidity('Por favor ingrese solo letras en el nombre');
        return;
    } else {
        nombres.setCustomValidity('');
    }
    
    // Validar apellidos
    if (!/^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/.test(apellidos.value)) {
        apellidos.setCustomValidity('Por favor ingrese solo letras en los apellidos');
        return;
    } else {
        apellidos.setCustomValidity('');
    }
    
    // Validar email
    if (!email.value.includes('@') || !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email.value)) {
        email.setCustomValidity('Por favor ingrese un correo electrónico válido');
        return;
    } else {
        email.setCustomValidity('');
    }
    
    // Validar teléfono
    if (telefono.value.length !== 10 || !/^\d+$/.test(telefono.value)) {
        telefono.setCustomValidity('El teléfono debe tener exactamente 10 dígitos');
        return;
    } else {
        telefono.setCustomValidity('');
    }
    
    // Validar contraseña
    if (password.value.length < 6) {
        password.setCustomValidity('La contraseña debe tener al menos 6 caracteres');
        return;
    } else {
        password.setCustomValidity('');
    }
    
    // Validar checkboxes
    if (!politicasCheck.checked || !terminosCheck.checked) {
        alert('Debe aceptar las políticas de privacidad y términos y condiciones');
        return;
    }

    // Si todas las validaciones pasan, aquí puedes enviar el formulario
    const formData = {
        nombres: nombres.value,
        apellidos: apellidos.value,
        email: email.value,
        telefono: telefono.value,
        password: password.value
    };
    
    console.log('Datos del formulario:', formData);
    // Aquí puedes agregar el código para enviar los datos al servidor
    // Por ejemplo:
    // fetch('/api/registro', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //     },
    //     body: JSON.stringify(formData)
    // });
});

// Función para mostrar mensajes de error personalizados
function mostrarError(input, mensaje) {
    const errorSpan = input.nextElementSibling;
    if (errorSpan && errorSpan.className === 'error-message') {
        errorSpan.textContent = mensaje;
        errorSpan.style.display = 'block';
    }
}

// Función para limpiar mensajes de error
function limpiarError(input) {
    const errorSpan = input.nextElementSibling;
    if (errorSpan && errorSpan.className === 'error-message') {
        errorSpan.style.display = 'none';
    }
}