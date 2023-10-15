function togglePagination(shouldShow) {
    const paginationContainer = document.getElementById('pagination');
    if (shouldShow) {
      paginationContainer.style.display = 'block';
    } else {
      paginationContainer.style.display = 'none';
    }
  }
  