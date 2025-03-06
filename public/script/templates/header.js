document.addEventListener('DOMContentLoaded', function() {
    // Se a largura da janela for menor que 768px, fecha o menu
    if (window.innerWidth < 768) {
      document.querySelector('.menu').classList.add('menu-closed');
    }
    
    // Evento de clique para alternar o menu (abrir/fechar)
    document.getElementById('menu').addEventListener('click', function() {
      document.querySelector('.menu').classList.toggle('menu-closed');
      const headerMenu = document.querySelector('.header_menu');
      headerMenu.classList.toggle('header_menu-expanded');
    });
  });