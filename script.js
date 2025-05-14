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


// ——— TIENDA y PRODUCTOS ———
async function cargarProductos(categoria = '') {
    try {
      let url = `getProductos.php${categoria ? '?categoria='+encodeURIComponent(categoria) : ''}`;
      const resp = await fetch(url, { credentials: 'same-origin' });
      if (!resp.ok) throw new Error(`Error HTTP ${resp.status}`);
      const html = await resp.text();
      document.getElementById('container').innerHTML = html;
  
      // Re-asignar listeners a los botones Agregar al carrito
      document.querySelectorAll('.agregarCarrito').forEach(btn =>
        btn.addEventListener('click', agregarAlCarrito)
      );
  
    } catch (err) {
      console.error(err);
      document.getElementById('container').innerHTML =
        '<p>Hubo un error cargando los productos.</p>';
    }
  }
  
  async function agregarAlCarrito(e) {
    const btn = e.currentTarget;
    const payload = {
      id_producto: btn.dataset.id,
      nombre:      btn.dataset.nombre,
      precio:      btn.dataset.precio
    };
    try {
      const resp = await fetch('agregarCarrito.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type':'application/json' },
        body: JSON.stringify(payload)
      });
      const data = await resp.json();
      if (!resp.ok) throw new Error(data.mensaje||'Error');
      mostrarNotificacion(`"${payload.nombre}" agregado 🤘`);
      cargarCarrito();
    } catch (err) {
      console.error(err);
      mostrarNotificacion(err.message,'error');
    }
  }
// ——— CARRITO ———
async function cargarCarrito() {
    try {
      const resp = await fetch('getCarrito.php', { credentials:'same-origin' });
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      const items = await resp.json();
      const tbody = document.getElementById('contenido-carrito');
      let total = 0;
      tbody.innerHTML = '';
      if (!items.length) {
        tbody.innerHTML = '<tr><td colspan="5">Tu carrito está vacío</td></tr>';
        document.getElementById('total-amount').textContent = '0.00';
        return;
      }
      items.forEach(i=>{
        total += parseFloat(i.subtotal);
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${i.nombre_producto}</td>
          <td>
            <button class="btn-cantidad" data-id="${i.id}" data-action="decrease">-</button>
            ${i.cantidad}
            <button class="btn-cantidad" data-id="${i.id}" data-action="increase">+</button>
          </td>
          <td>$${parseFloat(i.precio).toFixed(2)}</td>
          <td>$${parseFloat(i.subtotal).toFixed(2)}</td>
          <td><button class="btn-eliminar" data-id="${i.id}">🗑️</button></td>
        `;
        tbody.appendChild(tr);
      });
      document.getElementById('total-amount').textContent = total.toFixed(2);
  

document.querySelectorAll('.btn-cantidad').forEach(b =>
    b.addEventListener('click', actualizarCantidad)
  );
  document.querySelectorAll('.btn-eliminar').forEach(b =>
    b.addEventListener('click', eliminarDelCarrito)
  );
  
  
    } catch (err) {
      console.error(err);
      document.getElementById('contenido-carrito').innerHTML =
        '<tr><td colspan="5">Error cargando carrito</td></tr>';
    }
  }
  
  async function actualizarCantidad(e) {
    const { id, action } = e.currentTarget.dataset;
    try {
      const resp = await fetch('actualizarCantidad.php', {
        method: 'POST',
        credentials:'same-origin',
        headers:{ 'Content-Type':'application/json' },
        body: JSON.stringify({ id, action })
      });
      if (!resp.ok) throw new Error('No se actualizó cantidad');
      cargarCarrito();
    } catch (err) {
      console.error(err);
      mostrarNotificacion(err.message,'error');
    }
  }
  
  async function eliminarDelCarrito(e) {
    const id = e.currentTarget.dataset.id;
    try {
      const resp = await fetch(`eliminarCarrito.php?id=${id}`, {
        method:'DELETE',
        credentials:'same-origin'
      });
      if (!resp.ok) throw new Error('No se eliminó');
      mostrarNotificacion('Eliminado ✅');
      cargarCarrito();
    } catch (err) {
      console.error(err);
      mostrarNotificacion(err.message,'error');
    }
  }
  
  async function vaciarCarrito() {
    if (!confirm('¿Vaciar carrito?')) return;
    try {
      const resp = await fetch('vaciarCarrito.php', {
        method:'DELETE',
        credentials:'same-origin'
      });
      if (!resp.ok) throw new Error('No se vació');
      mostrarNotificacion('Carrito vaciado 🗑️');
      cargarCarrito();
    } catch (err) {
      console.error(err);
      mostrarNotificacion(err.message,'error');
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