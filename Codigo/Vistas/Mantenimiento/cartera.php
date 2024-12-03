<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monedero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6A3QJ7eEoy3d6nCmGJ4u7GnZl6lx6Jq0A6BXY5iYmI5NK9O3qhl9A8rRkH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 60px;
        }
        .wallet-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .wallet-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .balance {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-wallet {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="wallet-container">
        <h2 class="wallet-title">Monedero</h2>

        <!-- Ver Saldo -->
        <div class="balance">
            <p>Saldo actual:<span id="saldo">0.00</span>€</p>
        </div>

        <!-- Añadir Dinero -->
        <form id="depositForm">
            <div class="mb-3">
                <label for="amountDeposit" class="form-label">Añadir dinero</label>
                <input type="number" class="form-control" id="amountDeposit" name="amountDeposit" min="0" required>
            </div>
            <button type="submit" class="btn btn-success btn-wallet">Añadir al monedero</button>
        </form>

        <!-- Retirar Dinero -->
        <form id="withdrawForm">
            <div class="mb-3">
                <label for="amountWithdraw" class="form-label">Retirar dinero</label>
                <input type="number" class="form-control" id="amountWithdraw" name="amountWithdraw" min="0" required>
            </div>
            <button type="submit" class="btn btn-danger btn-wallet">Retirar del monedero</button>
        </form>

        <!-- Mensaje de error o éxito -->
        <div id="message" class="mt-3 text-center"></div>
    </div>

    <script src="./js/cartera.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6I3Q2neW9jY1l4+O3vFY7vZwggD2+1P6c+WfA4dPnFoeGm3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fq39J4kA4mI86eDvv6c2e26g4j4R30zGzH4hR9a4wVVo1" crossorigin="anonymous"></script>
</body>
</html>
