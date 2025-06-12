(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const currentURL = new URL(window.location.href).pathname;
    // Select all sidebar menu items
    const sidebarLinks = document.querySelectorAll('.sidebar-link');

    sidebarLinks.forEach((link) => {
      const linkPath = new URL(link.href).pathname;

      if (
        currentURL === '/item-catalog/item-management/edit-item/' &&
        linkPath === '/item-catalog/item-management/'
      ) {
        link.classList.add('bg-gray-100');
      }

      if (currentURL === linkPath) {
        link.classList.add('bg-gray-100');
      }
    });
    
    // Sidebar toggle
    const sidebarToggle = document.getElementById('desktop-sidebar-toggle');
    const sidebar = document.getElementById('logo-sidebar');
    const contentWrapper = document.querySelector('.content-wrapper');

    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('sm:translate-x-0');
      contentWrapper.classList.toggle('collapsed');
    });
    // Sidebar toggle end
  });


  
})();
