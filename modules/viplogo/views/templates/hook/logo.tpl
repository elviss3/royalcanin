


<div class="pos_logo product_block_container container">
	<div class="row pos_content">
		<div class="pos-logo">
			{*<div class="pos-title"><h2><span>{l s='Our Brands' mod='viplogo'}</span></h2></div>*}
			<div class="row pos-content">
				<div class="logo-slider owl-carousel">
					{foreach from=$logos item=logo name=vipLogo}
						<div class="item-banklogo">
							<a href ="{$link->getManufacturerLink($logo.id_manufacturer)}">
								{assign var="image" value="../img/m/{$logo.id_manufacturer}.jpg"}
								{if file_exists($image)}
									<img class="replace-2x img-responsive" src ="../img/m/{$logo.id_manufacturer}.jpg" alt ="{$logo.name}" />
								{else}
									<span>{$logo.name}</span>
								{/if}
							</a>
						</div>
					{/foreach}
				</div>
			</div>
		</div>

<a class="brands_all" href="{url entity='manufacturer'}"><span>zobacz wszystkich producentów</span></a>

	</div>
</div>


{literal}
<script type="text/javascript">
		$(document).ready(function() {
			var owl = $(".logo-slider");
			owl.owlCarousel({
				autoplay : {/literal}{$slideOptions.auto}{literal},
				smartSpeed: {/literal}{$slideOptions.speed_slide}{literal},
				autoPlayTimeout: {/literal}{$slideOptions.a_speed}{literal},
				autoplayHoverPause: true,
				items: {/literal}{$slideOptions.qty_products}{literal},
				nav: {/literal}{$slideOptions.show_nexback}{literal},
				dots : {/literal}{$slideOptions.show_control}{literal},
				loop: true,
				responsive:{
					0:{
						items:{/literal}{$slideOptions.itemsMobile}{literal},
					},
					480:{
						items:{/literal}{$slideOptions.itemsTablet}{literal},
					},
					768:{
						items:{/literal}{$slideOptions.itemsDesktopSmall}{literal},
						nav:false,
					},
					992:{
						items:{/literal}{$slideOptions.itemsDesktop}{literal}
					},
					1200:{
						items:{/literal}{$slideOptions.qty_products}{literal},
					}
				}
			});
		});
</script>
{/literal}

