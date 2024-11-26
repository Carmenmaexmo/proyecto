<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6A3QJ7eEoy3d6nCmGJ4u7GnZl6lx6Jq0A6BXY5iYmI5NK9O3qhl9A8rRkH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 60px;
        }
        .cart-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 60px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .cart-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .cart-item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
        .total-price {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
        }
        .btn-checkout {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="cart-container">
        <h2 class="cart-title">Tu Carrito de Compras</h2>

        <div id="cartItems"></div> <!-- Aquí se mostrarán los productos del carrito -->

        <div class="total-price">
            <p>Total: $<span id="totalPrice">0.00</span></p>
        </div>

        <button class="btn btn-primary btn-checkout" id="checkoutBtn">Proceder a Pagar</button>
    </div>

    <script>
        // Función para calcular el total
        function calculateTotal() {
            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            let total = 0;
            cart.forEach(item => {
                total += item.price * item.quantity;
            });
            return total.toFixed(2); // Devolver con dos decimales
        }

        // Función para mostrar los productos del carrito
        function displayCart() {
            const cartItemsDiv = document.getElementById("cartItems");
            cartItemsDiv.innerHTML = ""; // Limpiar los elementos anteriores

            const cart = JSON.parse(localStorage.getItem("cart")) || [];

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = "<p>No hay productos en el carrito.</p>";
            }

            cart.forEach(item => {
                const itemDiv = document.createElement("div");
                itemDiv.classList.add("cart-item");

                itemDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="ms-3">
                            <p>${item.name}</p>
                            <p>Precio: $${item.price.toFixed(2)}</p>
                        </div>
                    </div>
                    <div>
                        <p>Cantidad: <span class="quantity">${item.quantity}</span></p>
                        <p>Total: $${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                `;

                cartItemsDiv.appendChild(itemDiv);
            });

            // Actualizar el precio total
            document.getElementById("totalPrice").textContent = calculateTotal();
        }

        // Simulación de pago
        document.getElementById("checkoutBtn").addEventListener("click", function() {
            alert(`El total a pagar es $${calculateTotal()}. ¡Gracias por tu compra!`);
            // Vaciar carrito (opcional)
            localStorage.removeItem("cart");
            displayCart(); // Re-renderizar carrito vacío
        });

        // Mostrar los productos del carrito al cargar la página
        displayCart();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6I3Q2neW9jY1l4+O3vFY7vZwggD2+1P6c+WfA4dPnFoeGm3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fq39J4kA4mI86eDvv6c2e26g4j4R30zGzH4hR9a4wVVo1" crossorigin="anonymous"></script>
</body>
</html>
