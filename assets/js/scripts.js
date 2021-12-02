/**
 * Scripts
 */
$(document).ready(function () {
    var lazyLoadInstance = new LazyLoad({
        // Your custom settings go here
    });

    const image = document.getElementsByClassName('img-parallax');

    if($(window).width() <= 768) {
        new simpleParallax(image, {
            scale: 1.2
        });
    }

    if($(window).width() > 768) {
        new simpleParallax(image, {
            scale: 1.5
        });
    }

    if ($('.compartilhar-link').length > 0) {
        $('.compartilhar-link').on('click', function () {
            var value = $(this).data('link');

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
        });
    }


    $('#resize-logo').css('transform', '');

    $('.open-sub').on('click', function () {
        $('.menu-hide__sub').toggleClass('active')
    })

    if($('.cortina-efeito').hasClass('single-hero')) {
        document.body.style.minHeight = '6271px';
    }


    // acordion
    var section = $('.accordion li');

    function toggleAccordion() {
        section.removeClass('active');
        $(this).addClass('active');
    }

    section.on('click', toggleAccordion);

    $('.open-menu').on('click', function () {
        $('.menu-hide').addClass('open');
    })

    $('.close-menu').on('click', function () {
        $('.menu-hide').removeClass('open');
    })

    //galeria
    $('.post-galeria').each(function (){
        var $imagem = $(this).find('.galeria-content');
        var maxHeight = 0;

        $($imagem).each(function(){
            var thisH = $(this).find('img').height();

            console.log(':D');

            if (thisH > maxHeight) { maxHeight = thisH; }
        });

        $(this).height(maxHeight + 170);
    });

    //nova galeria
    $('.galeria-arrow button').on('click', function () {
        var $galeria = $(this).closest('.post-galeria');
        var $imagem = $galeria.find('.galeria-imagem');
        var $galeriaImagem = $galeria.find('.galeria-imagem.active');
        var $detalhes = $galeria.find('.legenda-credito.active');

        var galeriaQtd = parseInt($imagem.length);
        galeriaQtd = galeriaQtd - 1;


        //direita
        if ($(this).hasClass('right')) {
            if($galeriaImagem.next().length === 0 ) {
                $galeriaImagem.removeClass('active');
                $imagem.eq(0).addClass('active');
            } else {
                $galeriaImagem.removeClass('active');
                $galeriaImagem.next().addClass('active');
            }

            //legenda-credito
            if($detalhes.next().length === 0 ) {
                $detalhes.removeClass('active')
                $galeria.find('.legenda-credito').eq(0).addClass('active');
            } else {
                $detalhes.removeClass('active')
                $detalhes.next().addClass('active');
            }
        }

        //esquerda
        if ($(this).hasClass('left')) {
            if($galeriaImagem.prev().length === 0 ) {
                $galeriaImagem.removeClass('active');
                $imagem.eq(galeriaQtd).addClass('active');
            } else {
                $galeriaImagem.removeClass('active');
                $galeriaImagem.prev().addClass('active');
            }

            //legenda-credito
            if($detalhes.prev().length === 0 ) {
                $detalhes.removeClass('active')
                $galeria.find('.legenda-credito').eq(galeriaQtd).addClass('active');
            } else {
                $detalhes.removeClass('active')
                $detalhes.prev().addClass('active');
            }
        }
    });

    //listagem arquivo stories
    $('.bloco-ordenacao .titulo').on('click', function (){
       $('.ordenacao-int').toggleClass('active');
    });

    //escolhidas pela redacao
    if($('.outras-materias-int').length > 0) {
        var slider = tns({
            container: '.outras-materias-int',
            slideBy: 'page',
            loop: true,
            nav: false,
            gutter : 90,
            controlsContainer: ".outras-controls",
            items: 1
        });
    }

    //home slider de video
    if($('.video-bloco').length > 0) {
        var slider = tns({
            container: '.video-bloco',
            slideBy: 'page',
            loop: true,
            nav: false,
            gutter : 90,
            controlsContainer: ".video-controls",
            items: 1
        });
    }

    //legado especial
    if($('.especial-conteudo-list').length > 0) {
        var html = $('.especial-conteudo-list');
        var found = $(html).find("h2");

        var navegue = $('.navegue').html();

        found.each(function() {
            var texto = $(this).text();

            if(texto.length > 0) {
                listHtml = texto.replace(/\s/g, '');
                listHtml.replace(/<[^>]*>?/gm, '');

                $(this).attr('id', listHtml);

                navegue += '<a href="#'+listHtml+'" class="border-bottom border-dark py-2">'+texto+'</a>';
            }
        });

        $('.navegue').html(navegue);


    }

    //galeria legado

    const productCatsCarouselInit = () => {
        const productCatsSlider = document.querySelectorAll('.post-galeria-legado'); // container above slider
        productCatsSlider.forEach(sliderWrapper => {
            const slider = sliderWrapper.querySelector('.legado-slider'); // container with slider
            const controlsContainer = sliderWrapper.querySelector('.legado-controls');

            const catSlider = tns({
                container: slider,
                slideBy: 'page',
                loop: true,
                nav: false,
                gutter : 90,
                controlsContainer: controlsContainer,
                items: 1,
                autoHeight: true
            });
        });
    };
    if (document.querySelectorAll('.post-galeria-legado')) {
        productCatsCarouselInit();
    }

    //abrir / fechar search
    $('a.search').on('click', function () {
        $('.search-flutuante').css('transform', 'none');
    });

    $('a.close-search').on('click', function () {
        $('.search-flutuante').css('transform', 'translateY(-400px)');
    });

    //sua quebrada mobile
    if($(window).width() <= 768) {
        if($('.sua-quebrada-lista').length > 0) {
            var sliderQuebrada = tns({
                container: '.sua-quebrada-lista',
                items: 1,
                edgePadding : '50',
                loop: true,
                swipeAngle: false,
                speed: 400,
                mouseDrag: true,
                nav : false,
                controlsContainer: ".quebrada-controls",
                preventScrollOnTouch: 'auto'
            });

            sliderQuebrada.events.on('indexChanged', function (event){
                $('.sua-quebrada-lista .bloco').removeClass('active');
                $('.sua-quebrada-lista .bloco').eq(event.index).addClass('active');

                $('.zonas-mobile a').removeClass('active');
                $('.zonas-mobile a').eq((event.index - 2)).addClass('active');
            });
        }
    }

    //abre navegue especial
    if($(window).width() <= 768) {
        $('.especial-guia .titulo').on('click', function () {
            $('.navegue').toggleClass('active');
        });
    }

    //parametro qdo tiver o efeito cortina
    if($('.header-big').length > 0 ) {
        $('body').addClass('cortina-effect-page');
    }

    if ($(".wp-pagenavi").length > 0){
        $('.pagination-infi').infiniteScroll({
            path: '.nextpostslink',
            append: '.bloco',
            status: '.page-load-status',
            hideNav: '.wp-pagenavi',
            history: false
        });

        $('.pagination-infi').on( 'request.infiniteScroll', function( event, path ) {
            lazyLoadInstance.update();
        });
    }

    if ($(".paginacao-especial").length > 0){
        $('.pagination-infi').infiniteScroll({
            path: '.nextpostslink',
            append: '.bloco',
            status: '.page-load-status',
            hideNav: '.paginacao-especial',
            history: false
        });

        $('.pagination-infi').on( 'request.infiniteScroll', function( event, path ) {
            lazyLoadInstance.update();
        });
    }

    //sua quebrada
    $('.zonas-int .titulo').on('click', function () {
        //$('.zonas-int__bairro').removeClass('active');
        $(this).next().toggleClass('active');
    });

    $('.botoes-bloco a').on('click', function (){
        var href = $(this).data('href');

        $('.desc-'+href).slideDown();
    });

    $('.desc .close').on('click', function () {
        $(this).closest('.desc').slideUp();
    });

    $('.open-modal').on('click', function (){
       $('.apoiepop').fadeIn();
    });

    $('.close-modal').on('click', function (){
        $('.apoiepop').fadeOut();
    });
});


var especialHeight = $('.home__especial').height();
var menuHeight = $('.menu').height();
var barra = $('.barra-sobre-home');
var altura = $(window).height();
var lastScrollTop = 0;
if($('.home__podcast').length > 0) {
    var homePod = $('.home__podcast').offset().top;
}


$(window).scroll(function () {
    // efeito logo
    var element = document.querySelector('#resize-logo');
    var scrollY = $(window).scrollTop();


    if(scrollY >= altura + 10) {
        if($('body').hasClass('cortina-effect-page')){
            $('body').removeClass('cortina-effect-page')
        }

        if($('.cortina-efeito').hasClass('single-hero')) {
            $('.cortina-efeito').removeClass('single-hero-active');
            document.body.style.minHeight = '0px'
            $('.single-hero').css('margin-top',($(window).height() + 20)+ 'px');
        }
    }

    if(scrollY <= altura + 10) {
        if(!$('body').hasClass('cortina-effect-page')){
            $('body').addClass('cortina-effect-page')
        }

        if($('.cortina-efeito').hasClass('single-hero')) {
            $('.cortina-efeito').addClass('single-hero-active');
            document.body.style.minHeight = '6271px'
            $('.single-hero').css('margin-top','20px');
        }
    }

    // menu home
    if(element && barra && $(window).width() > 768) {
        if ((especialHeight + menuHeight) >= scrollY) {
            var height = (1 - $(window).scrollTop() / ($('#resize-logo').width() + $('#resize-logo').width() + 500));

            $('#resize-logo').css({
                'transform': 'scale(' + height + ')'
            });

           var myheight = $('#resize-logo')[0].getBoundingClientRect().height;
            $('.menu').height(myheight - 7);

            if(barra.hasClass('active')) {
                barra.removeClass('active');
            }
        } else {
            if(!barra.hasClass('active')) {
                barra.addClass('active');
            }
        }
    }

    if(!$('body').hasClass('cortina-effect-page')) {

        st = $(this).scrollTop();
        if(st < lastScrollTop) {
            if($('.menu').hasClass('hide-anima')) {
                $('.menu').removeClass('hide-anima');

                if ($('.sua-quebrada-page').length > 0) {
                    $('.sua-quebrada-page .position-sticky').css("z-index", "1");

                    if($(window).width() > 768) {
                        $('.sua-quebrada-page .position-sticky').css("top", "0px")
                    } else {
                        $('.sua-quebrada-page .position-sticky').css("top", "-35px")
                    }
                }
            }
        }
        if(st > lastScrollTop && scrollY > (altura / 2)) {
            if(!$('.menu').hasClass('hide-anima')) {
                $('.menu').addClass('hide-anima');

                if ($('.sua-quebrada-page').length > 0) {
                    $('.sua-quebrada-page .position-sticky').css("z-index", "3")


                        $('.sua-quebrada-page .position-sticky').css("top", "-100px")

                }
            }
        }
        lastScrollTop = st;
    }

    //Saiba mais home
    if ($('.home__podcast').length > 0 && $(window).width() > 768) {
        if ((homePod - altura) <= scrollY) {
            if(!$('.home__marquee').hasClass('hide-marquee')) {
                $('.home__marquee').addClass('hide-marquee');
            }
        } else {
            if($('.home__marquee').hasClass('hide-marquee')) {
                $('.home__marquee').removeClass('hide-marquee');
            }
        }
    }

    //menu mobile home
    if($(window).width() <= 768 && scrollY > 75) {
        if(!$('.menu-mobile').hasClass('active')) {
            $('.menu-mobile').addClass('active');
        }

        if(!$('.home__marquee').hasClass('active-mobile')) {
            $('.home__marquee').addClass('active-mobile');
        }
    }

    //sobe barra lateral qdo chega menu

    if(scrollY >= ($('.footer').offset().top - altura)) {
        if($('body').hasClass('home')) {
            if(barra.hasClass('active')) {
                barra.removeClass('active');
            }
        } else {
            $('.barra-sobre').fadeOut();
        }

        if($(window).width() <= 768) {
            if($('.menu-mobile').hasClass('active')) {
                $('.menu-mobile').removeClass('active');
            }

            if($('.home__marquee').hasClass('active-mobile')) {
                $('.home__marquee').removeClass('active-mobile');
            }
        }


    } else {
        if(!$('body').hasClass('home')) {
            if(!barra.hasClass('active')) {
                barra.addClass('active');
            }
        } else {
            $('.barra-sobre').fadeIn();
        }

        if($(window).width() <= 768) {
            if(!$('.menu-mobile').hasClass('active')) {
                $('.menu-mobile').addClass('active');
            }

            if(!$('.home__marquee').hasClass('active-mobile')) {
                $('.home__marquee').addClass('active-mobile');
            }
        }

    }
});

