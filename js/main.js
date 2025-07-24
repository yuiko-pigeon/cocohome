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


//ハンバーガーメニューを開いたまま、ウインドウサイズを大きくするとハンバーガーメニューが閉じる
const closeButton = document.querySelector('#vk-mobile-nav-menu-btn');
const nav = document.querySelector('#vk-mobile-nav');

window.addEventListener('resize', function() {

    // 1200px以上(PCサイズ）になった時だけ閉じる
    if(window.innerWidth >= 992) {
      nav.classList.remove('vk-mobile-nav-open');
      closeButton.classList.remove('menu-open');
      document.body.classList.remove('js-fix');
      document.documentElement.classList.remove('js-fix');
      menuOpen = false;
    }
  
});
