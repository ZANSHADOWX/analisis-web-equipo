
// ===== HOJAS DE CAFÉ ANIMADAS =====
function createCoffeeLeaves() {
    // Evitar duplicados
    if (document.querySelector('.coffee-leaves')) return;
    
    const leavesContainer = document.createElement('div');
    leavesContainer.className = 'coffee-leaves';
    document.body.appendChild(leavesContainer);
    
    const leafCount = 25; // Número de hojas
    
    for (let i = 0; i < leafCount; i++) {
        const leaf = document.createElement('div');
        leaf.classList.add('leaf');
        
        // Tamaño aleatorio
        const sizeRandom = Math.random();
        if (sizeRandom < 0.33) leaf.classList.add('leaf-small');
        else if (sizeRandom < 0.66) leaf.classList.add('leaf-medium');
        else leaf.classList.add('leaf-large');
        
        // Posición horizontal aleatoria
        const leftPos = Math.random() * 100;
        leaf.style.left = `${leftPos}%`;
        
        // Duración de animación aleatoria (entre 8 y 20 segundos)
        const duration = 8 + Math.random() * 12;
        leaf.style.animationDuration = `${duration}s`;
        
        // Retraso aleatorio
        const delay = Math.random() * 15;
        leaf.style.animationDelay = `${delay}s`;
        
        leavesContainer.appendChild(leaf);
    }
}

// ===== ANIMACIÓN DE CONFIRMACIÓN TIPO MERCADO PAGO =====
function showConfirmation(orderData) {
    // Eliminar overlay existente si lo hay
    const existingOverlay = document.querySelector('.confirmation-overlay');
    if (existingOverlay) existingOverlay.remove();
    
    const overlay = document.createElement('div');
    overlay.className = 'confirmation-overlay';
    overlay.innerHTML = `
        <div class="confirmation-card">
            <div class="checkmark-circle">
                <div class="checkmark"></div>
            </div>
            <h2>✅ ¡Pedido confirmado!</h2>
            <p><strong>Pedido #${orderData.id || 'N/A'}</strong></p>
            <p>Total: $${orderData.total || '0'}</p>
            <p>Método de pago: ${orderData.paymentMethod || 'Efectivo'}</p>
            <button class="ticket-button" onclick="window.location.href='ticket.php?id=${orderData.id}'">
                📄 Ver mi ticket
            </button>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    // Activar animación después de un micro-frame
    setTimeout(() => {
        overlay.classList.add('active');
    }, 10);
    
    // Cerrar al hacer clic fuera (opcional)
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeConfirmation();
        }
    });
    
    // Auto-cerrar después de 5 segundos y redirigir al ticket
    setTimeout(() => {
        if (document.querySelector('.confirmation-overlay')) {
            window.location.href = `ticket.php?id=${orderData.id}`;
        }
    }, 5000);
}

function closeConfirmation() {
    const overlay = document.querySelector('.confirmation-overlay');
    if (overlay) {
        overlay.classList.remove('active');
        setTimeout(() => overlay.remove(), 400);
    }
}

// Iniciar animaciones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    createCoffeeLeaves();
});