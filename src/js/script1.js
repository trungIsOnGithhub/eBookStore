function changeTheme(){
    var body = document.getElementsByTagName("body")[0];
    var labelE = document.getElementsByTagName("label")[0];
    if (body.className == "light-mode"){
      body.className = body.className.replace( /(?:^|\s)light-mode(?!\S)/g , '' );
      labelE.className += " switched";
    } else{
      body.className = "light-mode";
      labelE.className = "sidebar-themeLabel";
    }
  }
  
  $(".search-bar input")
   .focus(function () {
    $(".header").addClass("wide");
   })
   .blur(function () {
    $(".header").removeClass("wide");
   });
  
  
  
  function changeSidebarView(){
    var sidebar = document.getElementsByClassName("sidebar-container")[0];
    const viewButton = document.getElementsByClassName("sidebar-viewButton")[0];
    if (viewButton.title == "Shrink"){
      sidebar.className += " shrink";
      viewButton.ariaLabel = "Expand Sidebar";
      viewButton.title = "Expand";
    } else{
      sidebar.className = "sidebar-container ";
      viewButton.ariaLabel = "Shrink Sidebar";
      viewButton.title = "Shrink";
    }
  }
  
  var swiper = new Swiper('.product-slider', {
    spaceBetween: 30,
    effect: 'fade',
    // initialSlide: 2,
    loop: false,
    navigation: {
        nextEl: '.next',
        prevEl: '.prev'
    },
    // mousewheel: {
    //     // invert: false
    // },
    on: {
        init: function(){
            var index = this.activeIndex;
  
            var target = $('.product-slider__item').eq(index).data('target');
  
            console.log(target);
  
            $('.product-img__item').removeClass('active');
            $('.product-img__item#'+ target).addClass('active');
        }
    }
  
  });
  
  swiper.on('slideChange', function () {
    var index = this.activeIndex;
  
    var target = $('.product-slider__item').eq(index).data('target');
  
    console.log(target);
  
    $('.product-img__item').removeClass('active');
    $('.product-img__item#'+ target).addClass('active');
  
    if(swiper.isEnd) {
        $('.prev').removeClass('disabled');
        $('.next').addClass('disabled');
    } else {
        $('.next').removeClass('disabled');
    }
  
    if(swiper.isBeginning) {
        $('.prev').addClass('disabled');
    } else {
        $('.prev').removeClass('disabled');
    }
  });
  
  $(".js-fav").on("click", function() {
    $(this).find('.heart').toggleClass("is-active");
  });
  
  
  $(function () {
   $(".signin-button:not(.open)").on("click", function (e) {
    $(".overlay-app").addClass("is-active");
   });
   $(".pop-up .close-popup").click(function () {
    $(".overlay-app").removeClass("is-active");
   });
  });
  
  $(".signin-button:not(.open)").click(function () {
   $(".pop-up").addClass("visible");
  });
  
  $(".pop-up .close-popup").click(function () {
   $(".pop-up").removeClass("visible");
  });
  
  document.querySelector('.img__btn').addEventListener('click', function() {
    document.querySelector('.cont').classList.toggle('s--signup');
  });
  
  $(document).ready(function () {
    const $slider = $('.ad-slider');
    const winW = $(window).width();
    const animSpd = 750; // Change also in CSS
    const distOfLetGo = winW * 0.2;
    let curSlide = 1;
    let animation = false;
    let autoScrollVar = true;
    let diff = 0;
    let numOfAds = 5;
  
    // Navigation
    function bullets(dir) {
      $('.nav__slide--' + curSlide).removeClass('nav-active');
      $('.nav__slide--' + dir).addClass('nav-active');
    }
  
    function timeout() {
      animation = false;
    }
  
    function pagination(direction) {
      animation = true;
      diff = 0;
      $slider.addClass('animation');
      $slider.css({
        'transform': 'translate3d(-' + (curSlide - direction) * 100 + '%, 0, 0)' });
  
  
      $slider.find('.slide__darkbg').css({
        'transform': 'translate3d(' + (curSlide - direction) * 50 + '%, 0, 0)' });
  
    }
  
    function navigateRight() {
      if (!autoScrollVar) return;
      if (curSlide >= numOfAds) {
        let target = 1;
        bullets(target);
        curSlide = target;
        pagination(1);
        return;
      };
      pagination(0);
      setTimeout(timeout, animSpd);
      bullets(curSlide + 1);
      curSlide++;
    }
  
    function navigateLeft() {
      if (curSlide <= 1) {
        let target = numOfAds;
        bullets(target);
        curSlide = target;
        pagination(1);
        return;
      };
      pagination(2);
      setTimeout(timeout, animSpd);
      bullets(curSlide - 1);
      curSlide--;
    }
  
    function toDefault() {
      pagination(1);
      setTimeout(timeout, animSpd);
    }
  
    // Events
    $(document).on('mousedown touchstart', '.slide', function (e) {
      if (animation) return;
      let target = +$(this).attr('data-target');
      let startX = e.pageX || e.originalEvent.touches[0].pageX;
      $slider.removeClass('animation');
  
      $(document).on('mousemove touchmove', function (e) {
        let x = e.pageX || e.originalEvent.touches[0].pageX;
        diff = startX - x;
        if (target === 1 && diff < 0 || target === numOfAds && diff > 0) return;
  
        $slider.css({
          'transform': 'translate3d(-' + ((curSlide - 1) * 100 + diff / 30) + '%, 0, 0)' });
  
  
        $slider.find('.slide__darkbg').css({
          'transform': 'translate3d(' + ((curSlide - 1) * 50 + diff / 60) + '%, 0, 0)' });
  
      });
    });
  
    $(document).on('mouseup touchend', function (e) {
      $(document).off('mousemove touchmove');
  
      if (animation) return;
  
      if (diff >= distOfLetGo) {
        navigateRight();
      } else if (diff <= -distOfLetGo) {
        navigateLeft();
      } else {
        toDefault();
      }
    });
  
    $(document).on('click', '.nav__slide:not(.nav-active)', function () {
      let target = +$(this).attr('data-target');
      bullets(target);
      curSlide = target;
      pagination(1);
    });
  
   
    $(document).on('click', '.side-nav', function () {
      let target = $(this).attr('data-target');
  
      if (target === 'right') navigateRight();
      if (target === 'left') navigateLeft();
    });
  
    
    $(document).on('keydown', function (e) {
      if (e.which === 39) navigateRight();
      if (e.which === 37) navigateLeft();
    });
  /*
    $(document).on('mousewheel DOMMouseScroll', function (e) {
      if (animation) return;
      let delta = e.originalEvent.wheelDelta;
  
      if (delta > 0 || e.originalEvent.detail < 0) navigateLeft();
      if (delta < 0 || e.originalEvent.detail > 0) navigateRight();
    }); */
  });