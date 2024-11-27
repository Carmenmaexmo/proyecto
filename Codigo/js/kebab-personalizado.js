const API_KEBAB_URL = './api/ApiKebab.php'; // URL de la API para el Kebab
    let ingredientesSeleccionados = []; // Para almacenar los ingredientes seleccionados

    async function cargarKebabPersonalizado() {
        try {
            const response = await fetch(API_KEBAB_URL); // Solicita los kebabs
            const data = await response.json(); // Convierte la respuesta en JSON

            const kebabPersonalizado = data.find(kebab => kebab.nombre === "Kebab personalizado");

            if (kebabPersonalizado) {
                // Actualizar la imagen del kebab
                const imagenBase64 = kebabPersonalizado.foto;
                document.getElementById('kebab-imagen').src = `data:image/jpeg;base64,${imagenBase64}`;

                // Actualizar la descripción del kebab
                document.getElementById('kebab-descripcion').textContent = kebabPersonalizado.descripcion;
            } else {
                console.error("No se encontró el Kebab Personalizado en los datos.");
            }
        } catch (error) {
            console.error('Error al cargar la imagen y descripción del Kebab:', error);
        }
    }

    // Permitir arrastre
    function allowDrop(event) {
        event.preventDefault();
    }

    // Inicio del arrastre
    function drag(event) {
        event.dataTransfer.setData("text", event.target.closest(".ingredient-item").id);
    }

    // Acción de soltar para añadir ingredientes
    function drop(event) {
        event.preventDefault();
        const data = event.dataTransfer.getData("text");
        const selectedIngredients = document.getElementById("selected-ingredients");

        const ingredientElement = document.getElementById(data);
        const ingredientClone = ingredientElement.cloneNode(true);
        const precio = parseFloat(ingredientElement.getAttribute('data-precio')); // Asegurarse de que es un número
        const nombre = ingredientElement.querySelector('div').textContent.split(' - ')[0]; // Extraer el nombre del texto

        ingredientClone.id = `selected-${data}`;
        ingredientClone.classList.add("ingredient-in-dropzone");
        ingredientClone.draggable = false;

        ingredientClone.onclick = function () {
            removeIngredient(this.id, precio);
        };

        selectedIngredients.appendChild(ingredientClone);

        // Agregar el ingrediente al array de ingredientes seleccionados
        ingredientesSeleccionados.push({ id: data, nombre: nombre.toLowerCase(), precio: precio });

        // Actualizar el precio total
        updatePrice();
    }

    // Eliminar ingrediente y actualizar precio
    function removeIngredient(id, precio) {
        const ingredient = document.getElementById(id);
        if (ingredient) {
            ingredient.remove();
        }

        // Eliminar el ingrediente del array
        ingredientesSeleccionados = ingredientesSeleccionados.filter(item => item.id !== id.replace('selected-', ''));

        // Actualizar el precio total después de eliminar un ingrediente
        updatePrice();
    }

    // Actualizar el precio total
    function updatePrice() {
        let totalPrice = 0;
        
        // Sumar los precios de todos los ingredientes seleccionados
        ingredientesSeleccionados.forEach(item => {
            totalPrice += item.precio;  // Sumar el precio de cada ingrediente
        });

        // Obtener la cantidad del kebab
        const cantidad = parseInt(document.getElementById('cantidad').value, 10);
        
        // Si la cantidad no es un número, asignamos un valor por defecto de 1
        if (isNaN(cantidad)) {
            alert('La cantidad no es válida, se establecerá a 1.');
            totalPrice *= 1; // Por defecto, la cantidad es 1
        } else {
            totalPrice *= cantidad;  // Multiplicamos el precio total por la cantidad
        }

        // Actualizar el precio en el campo del formulario, asegurándonos de que el valor sea un número y solo el valor
        document.getElementById('precio').value = `${totalPrice.toFixed(2)}€`; // Mostramos el precio con el símbolo € al final
    }


    // Cargar los ingredientes desde la API
    async function loadIngredients() {
        try {
            const response = await fetch('./Api/ApiIngredientes.php');
            const ingredients = await response.json();

            const container = document.getElementById('ingredients-container');
            container.innerHTML = ''; // Limpia el contenedor

            ingredients.forEach((ingredient, index) => {
                const ingredientDiv = document.createElement('div');
                ingredientDiv.classList.add('ingredient-item');
                ingredientDiv.setAttribute('draggable', 'true');
                ingredientDiv.setAttribute('ondragstart', 'drag(event)');
                ingredientDiv.id = `ingredient-${index}`; // ID único para cada ingrediente
                ingredientDiv.setAttribute('data-precio', ingredient.precio); // Almacena el precio como atributo

                // Verifica si la imagen ya incluye el prefijo Base64
                const imageSrc = ingredient.foto.startsWith('data:image')
                    ? ingredient.foto
                    : `data:image/jpeg;base64,${ingredient.foto}`;

                // Crear el contenido del ingrediente
                ingredientDiv.innerHTML = `
                    <img src="${imageSrc}" alt="${ingredient.nombre}" class="ingredient-img">
                    <div>${ingredient.nombre} - ${ingredient.precio.toFixed(2)}€</div>
                `;

                container.appendChild(ingredientDiv);
            });
        } catch (error) {
            console.error('Error al cargar los ingredientes:', error);
        }
    }

    // Mostrar u ocultar alérgenos
    document.querySelectorAll('input[name="alergeno"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            const alergenoList = document.getElementById('alergeno-list');
            alergenoList.style.display = event.target.value === "si" ? 'block' : 'none';
        });
    });

    // Llamar a las funciones al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        cargarKebabPersonalizado(); // Carga la imagen y descripción del kebab
        loadIngredients(); // Carga los ingredientes
    });

    //Añadir un nuevo kebab personalizado al carrito
   // Añadir un nuevo kebab personalizado al carrito
function añadirKebabPersonalizadoAlCarrito(event) {
    event.preventDefault(); // Evita que se recargue la página al enviar el formulario
    
    // Verificar ingredientes seleccionados
    if (ingredientesSeleccionados.length < 3) {
        alert('Debes seleccionar al menos 3 ingredientes, incluido un pan.');
        return; // Salir de la función si no hay suficientes ingredientes
    }

    // Verificar si hay pan
    const tienePan = ingredientesSeleccionados.some(ingrediente => ingrediente.nombre.includes('pan'));
    if (!tienePan) {
        alert('Debes incluir al menos un tipo de pan.');
        return; // Salir de la función si no hay pan
    }

    // Obtener detalles del kebab personalizado
    const nombre = 'Kebab Personalizado (' + ingredientesSeleccionados.map(i => i.nombre).join(', ') + ')';
    const imagen = document.getElementById('kebab-imagen').src; // Imagen en Base64
    
    // Obtener el precio calculado dinámicamente desde el campo de texto
    let precio = document.getElementById('precio').value;

    // Limpiar el precio para eliminar el símbolo de euro (€) y otros caracteres no numéricos
    precio = parseFloat(precio.replace(/[^\d.-]/g, '')); // Expresión regular para eliminar todo lo que no sea un número
    
    // Verificar que el precio es un número válido
    if (isNaN(precio)) {
        alert('El precio no es válido.');
        return; // Salir si el precio no es válido
    }

    // Obtener la cantidad del campo de cantidad
    const cantidad = parseInt(document.getElementById('cantidad').value) || 1;

    // Verificar si la cantidad es válida
    if (cantidad <= 0) {
        alert('Por favor, selecciona una cantidad válida.');
        return; // Salir si la cantidad no es válida
    }

    // Crear el objeto del producto con el precio calculado y la cantidad
    const producto = {
        id: Date.now(), // ID único para este producto
        nombre,
        imagen: imagen.split(',')[1], // Base64 (extraído del src)
        cantidad, // Aquí obtenemos la cantidad desde el campo de cantidad
        precioUnitario: precio,
    };

    console.log('Producto a añadir:', producto);

    // Recuperar el carrito actual desde localStorage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Añadir el nuevo producto al carrito
    carrito.push(producto);

    // Guardar el carrito actualizado en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Actualizar el contador del carrito
    actualizarContadorCarrito(carrito);

    // Mostrar mensaje al usuario
    alert('Kebab Personalizado añadido al carrito');
}

    
    
    