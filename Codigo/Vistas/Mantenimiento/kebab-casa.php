<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kebab de la Casa</title>
  <style>
   body {
    font-family: Arial, sans-serif;
    background-color: cornflowerblue;
    margin: 0;
    padding: 0;
}

header {
    padding: 1rem;
}

#kebabs-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 2rem;
}

.kebab-card {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin: 1rem;
    padding: 1rem;
    width: 300px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.kebab-card img {
    width: 100%;
    border-radius: 8px;
}

.kebab-card h3 {
    margin: 0.5rem 0;
    color: #333;
}

.kebab-card p {
    margin: 0.5rem 0;
    color: #666;
}

.kebab-card span {
    display: inline-block;
    margin-top: 0.5rem;
    font-weight: bold;
    color: cornflowerblue;
}

/* Ingredientes con imágenes */
.kebab-card ul {
    list-style-type: none;
    padding: 0;
    margin: 0.5rem 0;
}

.kebab-card li {
    display: flex;
    align-items: center;
    margin: 0.5rem 0;
}

.kebab-card .ingredient-image {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

/* Botones de cantidad */
.quantity-btn {
    padding: 5px 10px;
    margin: 5px;
    background-color: cornflowerblue;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.quantity-btn:hover {
    background-color: #1e70b3;
}

.quantity-input {
    width: 50px;
    text-align: center;
    padding: 5px;
    margin: 5px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

/* Botón añadir al carrito */
.add-to-cart-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px;
    width: 100%;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1rem;
}

.add-to-cart-btn:hover {
    background-color: #218838;
}

.add-to-cart-btn:active {
    background-color: #1e7e34;
}

  </style>
</head>
<body>
  <header>
    Kebabs de la Casa
  </header>
  <main>
    <div id="kebabs-container">
      <!-- Las tarjetas de kebabs se cargarán aquí -->
    </div>
  </main>

  <script src="./js/kebab-lacasa.js"></script>
</body>
</html>
