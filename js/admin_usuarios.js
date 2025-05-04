document.addEventListener('DOMContentLoaded', () => {
    const tabCreate = document.getElementById('tab-create');
    const tabManage = document.getElementById('tab-manage');
    const contentCreate = document.getElementById('content-create');
    const contentManage = document.getElementById('content-manage');
    const form = contentCreate.querySelector('form');
    const formTypeInput = form.querySelector('input[name="form_type"]');
    const submitButton = form.querySelector('button[type="submit"]');
  
    // Elementos de búsqueda en la tabla
    const searchContainer = document.querySelector('.search-container');
    const searchInput = document.getElementById('user-search');
    const table = document.querySelector('#content-manage table');
  
    // Funciones para cambiar pestañas
    function showCreateTab() {
      tabCreate.classList.add('active');
      tabManage.classList.remove('active');
      contentCreate.classList.add('active');
      contentManage.classList.remove('active');
      // Ocultar buscador
      if (searchContainer) searchContainer.style.display = 'none';
    }
  
    function showManageTab() {
      tabManage.classList.add('active');
      tabCreate.classList.remove('active');
      contentManage.classList.add('active');
      contentCreate.classList.remove('active');
      // Mostrar buscador
      if (searchContainer) searchContainer.style.display = '';
    }
  
    // Resetear el formulario a modo creación
    function resetToCreateMode() {
      const userIdInput = form.querySelector('input[name="user_id"]');
      if (userIdInput) userIdInput.remove();
      formTypeInput.value = 'create';
      submitButton.textContent = 'Crear Usuario';
      form.querySelectorAll('input').forEach(input => {
        if (input.name !== 'form_type' && input.type !== 'hidden') input.value = '';
      });
      form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    }
  
    // Filtrar usuarios en la tabla, excluyendo la columna de acciones
    function filterUsers() {
      const filter = searchInput.value.toLowerCase();
      const rows = table.tBodies[0].rows;
      Array.from(rows).forEach(row => {
        // Seleccionar todas las celdas excepto la de acciones (class="actions")
        const cells = row.querySelectorAll('td:not(.actions)');
        const text = Array.from(cells)
          .map(td => td.textContent.toLowerCase())
          .join(' ');
        row.style.display = text.includes(filter) ? '' : 'none';
      });
    }
  
    // Eventos de pestañas
    tabCreate.addEventListener('click', () => {
      if (tabCreate.classList.contains('active') && formTypeInput.value === 'update') {
        resetToCreateMode();
      }
      showCreateTab();
    });
  
    tabManage.addEventListener('click', showManageTab);
  
    // Inicializar estado
    showCreateTab();
  
    // Configurar buscador
    if (searchContainer) {
      searchContainer.style.display = 'none';
    }
    if (searchInput) {
      searchInput.addEventListener('input', filterUsers);
    }
  });
  