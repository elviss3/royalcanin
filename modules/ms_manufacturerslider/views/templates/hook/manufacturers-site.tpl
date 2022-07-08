
{if $manufacturers}
    <div id="ybc-mnf-block">
        <ul id="ybc-mnf-block-ul" class="owl-carousel">
        	{foreach from=$manufacturers item=manufacturer}
        		<li class="ybc-mnf-block-li{if $YBC_MF_SHOW_NAME} ybc_mnf_showname{/if}">
                    <a class="ybc-mnf-block-a-img" href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">
                        <img src="{$manufacturer.image nofilter}" title="{$manufacturer.name|escape:'html':'UTF-8'}" alt="{$manufacturer.name|escape:'html':'UTF-8'}"/>
                    </a>
                    {if $YBC_MF_SHOW_NAME}
                        <a class="ybc-mnf-block-a-name" href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'html'}">
                            {$manufacturer.name|escape:'html':'UTF-8'}
                        </a>
                    {/if}
                </li>
        	{/foreach}
        </ul>
    </div> 
{/if}
<script type="text/javascript">
    var YBC_MF_PER_ROW_DESKTOP = {$YBC_MF_PER_ROW_DESKTOP|intval};
    var YBC_MF_PER_ROW_MOBILE = {$YBC_MF_PER_ROW_MOBILE|intval};
    var YBC_MF_PER_ROW_TABLET = {$YBC_MF_PER_ROW_TABLET|intval};
    var YBC_MF_SHOW_NAV = {if $YBC_MF_SHOW_NAV}true{else}false{/if};
    var YBC_MF_AUTO_PLAY = {if $YBC_MF_AUTO_PLAY}true{else}false{/if};
    var YBC_MF_PAUSE = {if $YBC_MF_PAUSE}true{else}false{/if};
    var YBC_MF_SPEED = {$YBC_MF_SPEED|intval};

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

</script>