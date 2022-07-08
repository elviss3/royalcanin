
$(document).ready(function(){
    if ($('#ybc-mnf-block-ul').length > 0)
    {
        var nbImageSlide = $("#ybc-mnf-block-ul>li").length;
    	$("#ybc-mnf-block-ul").owlCarousel({ 
    	    responsive : {
                1199 : {
                    items : YBC_MF_PER_ROW_DESKTOP < nbImageSlide ? YBC_MF_PER_ROW_DESKTOP : nbImageSlide,
                    loop: YBC_MF_PER_ROW_DESKTOP < nbImageSlide ? true : false,
                },
                480 : {
                    items : YBC_MF_PER_ROW_TABLET < nbImageSlide ? YBC_MF_PER_ROW_TABLET : nbImageSlide,
                    loop:  YBC_MF_PER_ROW_TABLET < nbImageSlide ? true : false,
                },
    	        0 : {
                    items : YBC_MF_PER_ROW_MOBILE < nbImageSlide ? YBC_MF_PER_ROW_MOBILE : nbImageSlide,
                    loop: YBC_MF_PER_ROW_MOBILE < nbImageSlide ? true : false,
                }
    	    },
            items : YBC_MF_PER_ROW_DESKTOP < nbImageSlide ? YBC_MF_PER_ROW_DESKTOP : nbImageSlide,
            // Navigation
            nav: YBC_MF_SHOW_NAV,
            navText: ['<i class="material-icons"></i>','<i class="material-icons"></i>'],
            navContainerClass: 'owl-nav-custom d-lg-none',
		    navClass: [ 'owl-prev-custom homeslider__arrow', 'owl-next-custom homeslider__arrow' ],
            margin: 30,
            autoplay: YBC_MF_AUTO_PLAY,
            autoplayHoverPause: YBC_MF_PAUSE,
            autoplayTimeout: YBC_MF_SPEED,
            loop: false,
        });
     }
});