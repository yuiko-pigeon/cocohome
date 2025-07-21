document.addEventListener('DOMContentLoaded', function () {
    const acordionOpen = document.querySelector('#vk-mobile-nav-menu-btn');
  
    if (acordionOpen) {
      acordionOpen.addEventListener('click', function () {
        setTimeout(() => {
          if (this.classList.contains('menu-open')) {
            document.body.classList.add('js-fix');
            document.documentElement.classList.add('js-fix');
          } else {
            document.body.classList.remove('js-fix');
            document.documentElement.classList.remove('js-fix');
          }
        }, 100); // クリック後にクラスが付くのを待つ
      });
    }
  });