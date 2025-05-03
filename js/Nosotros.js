//*nosotros.*//

document.addEventListener("DOMContentLoaded", function () {
    const searchIcon = document.querySelector(".search-field i.bx-search");
    const searchInput = document.querySelector(".search-field input");
  
    searchIcon.addEventListener("click", function () {
        let query = searchInput.value.trim(); // Obtiene el texto ingresado en la barra de búsqueda
  
        if (query !== "") {
            alert("Buscando: " + query);
            // Aquí puedes redirigir a una página con los resultados
            // window.location.href = "resultados.html?q=" + encodeURIComponent(query);
        } else {
            alert("Por favor, ingresa un término de búsqueda.");
        }
    });
  });
  searchInput.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
        searchIcon.click(); // Simula el clic en el icono de búsqueda
    }
  });