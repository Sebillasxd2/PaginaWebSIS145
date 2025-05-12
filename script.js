// Variables globales
let currentSlide = 0;
let slideInterval;

// Funciones del slider
function startSlider() {
    stopSlider();
    slideInterval = setInterval(nextSlide, 5000);
}

function stopSlider() {
    if (slideInterval) {
        clearInterval(slideInterval);
    }
}

function nextSlide() {
    const slides = document.querySelectorAll('.slide');
    const buttons = document.querySelectorAll('.slide-button');
    const nextIndex = (currentSlide + 1) % slides.length;
    goToSlide(nextIndex);
}

function prevSlide() {
    const slides = document.querySelectorAll('.slide');
    const buttons = document.querySelectorAll('.slide-button');
    const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
    goToSlide(prevIndex);
}

function goToSlide(index) {
    const slides = document.querySelectorAll('.slide');
    const buttons = document.querySelectorAll('.slide-button');
    
    stopSlider();
    
    slides[currentSlide].classList.remove('active');
    buttons[currentSlide].classList.remove('active');
    
    currentSlide = index;
    
    slides[currentSlide].classList.add('active');
    buttons[currentSlide].classList.add('active');
    
    setTimeout(startSlider, 5000);
}

/// Funciones de la tienda
async function cargarProductos(categoria = '') {
    try {
        let url = 'index.php';
        if (categoria) {
            url += `?categoria=${encodeURIComponent(categoria)}`;
        }

        const response = await fetch(url);
        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);

        // Aquí cambiamos a text()
        const html = await response.text();
        const container = document.getElementById('container');
        container.innerHTML = html;

        // Re-asignar listeners de "Agregar al carrito" si tu HTML los incluye
        container.querySelectorAll('.agregarCarrito').forEach(button =>
            button.addEventListener('click', agregarAlCarrito)
        );
    } catch (error) {
        console.error('Error al cargar productos:', error);
        document.getElementById('container').innerHTML =
            '<p>Hubo un error cargando los productos. Por favor intenta nuevamente.</p>';
    }
}


async function agregarAlCarrito(event) {
    const button = event.currentTarget;
    const productoId = button.getAttribute('data-id');
    const productoNombre = button.getAttribute('data-nombre');
    const productoPrecio = button.getAttribute('data-precio');

    try {
        const response = await fetch('agregarCarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_producto: productoId,
                nombre: productoNombre,
                precio: productoPrecio
            })
        });

        const data = await response.json();
        
        if (response.ok) {
            mostrarNotificacion(`"${productoNombre}" agregado al carrito`);
            cargarCarrito();
        } else {
            throw new Error(data.mensaje || 'Error al agregar al carrito');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion(error.message, 'error');
    }
}

// Funciones del carrito
async function cargarCarrito() {
    try {
        const response = await fetch('getCarrito.php');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        const carritoTableBody = document.getElementById('contenido-carrito');
        let total = 0;
        
        carritoTableBody.innerHTML = '';
        
        if (!data || data.length === 0) {
            carritoTableBody.innerHTML = '<tr><td colspan="5">Tu carrito está vacío</td></tr>';
            document.getElementById('total-amount').textContent = '0.00';
            return;
        }
        
        data.forEach(item => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${item.nombre_producto}</td>
                <td>
                    <button class="btn-cantidad" data-id="${item.id}" data-action="decrease">-</button>
                    ${item.cantidad}
                    <button class="btn-cantidad" data-id="${item.id}" data-action="increase">+</button>
                </td>
                <td>$${item.precio.toFixed(2)}</td>
                <td>$${item.subtotal.toFixed(2)}</td>
                <td><button class="btn-eliminar" data-id="${item.id}">Eliminar</button></td>
            `;
            carritoTableBody.appendChild(fila);
            total += item.subtotal;
        });

        document.getElementById('total-amount').textContent = total.toFixed(2);
        
        // Agregar event listeners a los botones de cantidad y eliminar
        document.querySelectorAll('.btn-cantidad').forEach(button => {
            button.addEventListener('click', actualizarCantidad);
        });
        
        document.querySelectorAll('.btn-eliminar').forEach(button => {
            button.addEventListener('click', eliminarDelCarrito);
        });
    } catch (error) {
        console.error('Error al cargar el carrito:', error);
        document.getElementById('contenido-carrito').innerHTML = 
            '<tr><td colspan="5">Hubo un error cargando el carrito</td></tr>';
    }
}

async function actualizarCantidad(event) {
    const button = event.currentTarget;
    const productoId = button.getAttribute('data-id');
    const action = button.getAttribute('data-action');
    
    try {
        const response = await fetch('actualizarCantidad.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: productoId,
                action: action
            })
        });
        
        if (response.ok) {
            cargarCarrito();
        } else {
            throw new Error('Error al actualizar la cantidad');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion(error.message, 'error');
    }
}

async function eliminarDelCarrito(event) {
    const button = event.currentTarget;
    const productoId = button.getAttribute('data-id');
    
    try {
        const response = await fetch(`eliminarDelCarrito.php?id=${productoId}`, {
            method: 'DELETE'
        });
        
        if (response.ok) {
            mostrarNotificacion('Producto eliminado del carrito');
            cargarCarrito();
        } else {
            throw new Error('Error al eliminar el producto');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion(error.message, 'error');
    }
}

async function vaciarCarrito() {
    try {
        const confirmacion = confirm('¿Estás seguro de que quieres vaciar el carrito?');
        if (!confirmacion) return;
        
        const response = await fetch('vaciarCarrito.php', {
            method: 'DELETE'
        });
        
        if (response.ok) {
            mostrarNotificacion('Carrito vaciado correctamente');
            cargarCarrito();
        } else {
            throw new Error('Error al vaciar el carrito');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarNotificacion(error.message, 'error');
    }
}

// Funciones de proveedores
async function cargarProveedores() {
    try {
        const response = await fetch('getProveedores.php');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        const proveedoresContainer = document.getElementById('lista-proveedores');
        
        proveedoresContainer.innerHTML = '';
        
        if (!data || data.length === 0) {
            proveedoresContainer.innerHTML = '<p>No hay proveedores disponibles en este momento.</p>';
            return;
        }
        
        data.forEach(proveedor => {
            const proveedorElement = document.createElement('div');
            proveedorElement.classList.add('proveedor');
            
            proveedorElement.innerHTML = `
                <div class="proveedor-logo-container">
                    <img src="${proveedor.logo_url}" alt="${proveedor.nombre} logo" class="proveedor-logo">
                </div>
                <h3>${proveedor.nombre}</h3>
                <p class="proveedor-descripcion">${proveedor.descripcion || ''}</p>
            `;
            
            proveedoresContainer.appendChild(proveedorElement);
        });
    } catch (error) {
        console.error('Error al cargar los proveedores:', error);
        document.getElementById('lista-proveedores').innerHTML = 
            '<p>Hubo un error cargando los proveedores. Por favor intenta nuevamente.</p>';
    }
}

// Función de ubicación
async function cargarUbicacion() {
    try {
        const response = await fetch('getUbicacion.php');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        document.getElementById('descripcion-ubicacion').textContent = data.descripcion;
        document.getElementById('mapa-ubicacion').src = data.url;
    } catch (error) {
        console.error('Error al cargar la ubicación:', error);
        document.getElementById('descripcion-ubicacion').textContent = 
            'No se pudo cargar la información de ubicación.';
    }
}

// Función de notificación
function mostrarNotificacion(mensaje, tipo = 'success') {
    const notificacion = document.createElement('div');
    notificacion.className = `notificacion notificacion-${tipo}`;
    notificacion.textContent = mensaje;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.classList.add('mostrar');
    }, 10);
    
    setTimeout(() => {
        notificacion.classList.remove('mostrar');
        setTimeout(() => {
            document.body.removeChild(notificacion);
        }, 300);
    }, 3000);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Iniciar slider
    startSlider();
    
    // Cargar datos iniciales
    cargarProductos();
    cargarCarrito();
    cargarProveedores();
    cargarUbicacion();
    
    // Event listeners para botones del slider
    document.querySelectorAll('.slide-button').forEach(button => {
        button.addEventListener('click', (e) => {
            goToSlide(parseInt(e.target.getAttribute('data-slide')));
        });
    });
    
    // Event listener para vaciar carrito
    document.getElementById('vaciar-carrito').addEventListener('click', vaciarCarrito);
    
    // Event listeners para menú desplegable
    document.querySelectorAll('.dropdown-content a[data-categoria]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const categoria = e.target.getAttribute('data-categoria');
            cargarProductos(categoria);
        });
    });
});