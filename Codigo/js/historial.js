document.addEventListener('DOMContentLoaded', function () {
    const idUsuario = localStorage.getItem('idUsuario');
    console.log('ID del usuario:', idUsuario);
    const orderHistoryContainer = document.getElementById('order-history-container');
    const orderHistoryList = document.getElementById('order-history-list');

    if (!idUsuario) {
        alert('No se ha encontrado el ID del usuario.');
        window.location.href = '?menu=login.php';
        return;
    }

    // Cargar el historial de pedidos
    function loadOrderHistory() {
        fetch(`./api/ApiPedidos.php/usuario/${idUsuario}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    // Ordenar los pedidos por fecha en orden descendente
                    data.sort((a, b) => new Date(b.fechaHora) - new Date(a.fechaHora));
                    console.log('Pedidos ordenados:', data);
                    // Crear la lista de pedidos
                    const orderListHTML = data.map(pedido => `
                        <li class="order-item">
                            <div class="order-date">Fecha: ${new Date(pedido.fechaHora).toLocaleString()}</div>
                            <div class="order-status">Estado: ${pedido.estado}</div>
                            <div class="order-total">Precio Total: €${pedido.precioTotal.toFixed(2)}</div>
                        </li>
                    `).join('');

                    orderHistoryList.innerHTML = orderListHTML;
                } else {
                    orderHistoryList.innerHTML = '<li>No hay pedidos en el historial.</li>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                orderHistoryList.innerHTML = '<li>Hubo un error al cargar el historial de pedidos.</li>';
            });
    }

    // Llamar a la función para cargar el historial de pedidos
    loadOrderHistory();
});
