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

        const ingredient = document.getElementById(data).cloneNode(true);
        const precio = parseFloat(document.getElementById(data).getAttribute('data-precio'));

        ingredient.id = `selected-${data}`;
        ingredient.classList.add("ingredient-in-dropzone");
        ingredient.draggable = false;

        ingredient.onclick = function() { 
            removeIngredient(this.id, precio); 
        };

        selectedIngredients.appendChild(ingredient);

        // Agregar el ingrediente al array
        ingredientesSeleccionados.push({ id: data, precio: precio });

        // Actualizar el precio
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

        // Actualizar el precio
        updatePrice();
    }

    // Actualizar el precio total
    function updatePrice() {
        let totalPrice = 0;
        ingredientesSeleccionados.forEach(item => {
            totalPrice += item.precio;
        });

        const cantidad = document.getElementById('cantidad').value;
        totalPrice *= cantidad; // Multiplicar por la cantidad

        // Mostrar el precio total en el formulario
        document.getElementById('precio').value = `${totalPrice.toFixed(2)}€`;
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