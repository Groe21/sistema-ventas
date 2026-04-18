/**
 * Onboarding Tour - Sistema Comercial Pro
 * Interactive guided tour for new users using Driver.js
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if we should show the onboarding tour
    const showTour = document.body.dataset.showOnboardingTour === 'true';
    const business = window.business || {};
    
    if (!showTour || business.onboarding_completed) {
        return; // Don't show tour if already completed or not requested
    }
    
    // Initialize Driver.js
    const driver = window.driver({
        showProgress: true,
        showButtons: ['next', 'previous', 'close'],
        nextBtnText: 'Siguiente →',
        prevBtnText: '← Anterior',
        doneBtnText: '¡Entendido!',
        closeBtnText: '×',
        progressText: 'Paso {{current}} de {{total}}',
        allowClose: true,
        overlayOpacity: 0.7,
        
        onDestroyStarted: () => {
            // Mark onboarding as completed when tour finishes
            markOnboardingCompleted();
            driver.destroy();
        },
        
        steps: [
            {
                element: '#dashboard-welcome',
                popover: {
                    title: '¡Bienvenido a tu Dashboard! 🎉',
                    description: 'Aquí encontrarás un resumen de las estadísticas más importantes de tu negocio: ventas del día, productos más vendidos y clientes principales.',
                    position: 'bottom',
                }
            },
            {
                element: '#sidebar-products, [href*="products"], a[href*="productos"]',
                popover: {
                    title: '📦 Gestión de Productos',
                    description: 'Empieza agregando tus productos o servicios. Puedes definir precios, stock, categorías y códigos de barras para una gestión completa de tu inventario.',
                    position: 'right',
                }
            },
            {
                element: '#sidebar-customers, [href*="customers"], a[href*="clientes"]',
                popover: {
                    title: '👥 Registro de Clientes',
                    description: 'Crea tu base de datos de clientes. Guarda su información de contacto, historial de compras y ofréceles programas de lealtad personalizados.',
                    position: 'right',
                }
            },
            {
                element: '#sidebar-pos, [href*="pos"], a[href*="ventas"]',
                popover: {
                    title: '💰 Punto de Venta (POS)',
                    description: 'Aquí realizarás tus ventas de forma rápida y sencilla. Busca productos, aplica descuentos, acepta diferentes métodos de pago y genera facturas automáticamente.',
                    position: 'right',
                }
            },
            {
                element: '#sidebar-reports, [href*="reports"], a[href*="reportes"]',
                popover: {
                    title: '📊 Reportes y Estadísticas',
                    description: 'Analiza el rendimiento de tu negocio con reportes detallados de ventas, productos, clientes y más. Toma decisiones informadas basadas en datos reales.',
                    position: 'right',
                }
            },
            {
                element: '#sidebar-settings, [href*="settings"], a[href*="configuracion"]',
                popover: {
                    title: '⚙️ Configuración',
                    description: 'Personaliza tu negocio: datos fiscales, impuestos, formatos de factura, métodos de pago y mucho más. ¡Todo adaptado a tus necesidades!',
                    position: 'right',
                }
            },
            {
                popover: {
                    title: '🚀 ¡Estás listo para empezar!',
                    description: 'Recuerda que tienes 14 días de prueba gratis para explorar todas las funcionalidades. Si necesitas ayuda, nuestro equipo de soporte está disponible. ¡Éxitos en tu negocio!',
                }
            }
        ]
    });
    
    // Start the tour with a small delay to allow page to fully load
    setTimeout(() => {
        driver.drive();
    }, 500);
});

/**
 * Mark the onboarding as completed in the backend
 */
function markOnboardingCompleted() {
    fetch('/api/complete-onboarding', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Onboarding completed successfully');
            // Update the dataset to prevent tour from showing again
            if (document.body.dataset) {
                document.body.dataset.showOnboardingTour = 'false';
            }
        }
    })
    .catch(error => {
        console.error('❌ Error marking onboarding as completed:', error);
    });
}
