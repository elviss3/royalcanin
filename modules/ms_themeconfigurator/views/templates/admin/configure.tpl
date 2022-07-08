{*
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Konfiguracja storny głównej' mod='ms_themeconfigurator'}</h3>
	<p>
		<strong>{l s='Skonfiguruj szablon szybko i prosto!' mod='ms_themeconfigurator'}</strong><br />
		{l s='Poniżej pokazany jest blokowy układ strony głównej oraz opisane jej elementy.' mod='ms_themeconfigurator'}<br />
		{l s='Poszczególne elementy wyszczególnione są do konfiguracji w bezpośrednich linkach do modułów czy ustawień.' mod='ms_themeconfigurator'}
	</p>
	<br />

	<div class="row">
		<div class="themes_view col-md-6">
			<div class="row topheader"><span>Top header</span>
				<div class="col-sm-12 element border h50"></div>
			</div>
			<div class="row main_menu"><span>Menu</span>
				<div class="col-sm-12 element border h25"></div>
			</div>
			<div class="row slider"><span>Slider</span>
				<div class="col-sm-12 element border h100"></div>
			</div>
			<div class="row marki"><span>Strefa marek</span>
				<div class="col-sm-12 element border h50"></div>
			</div>
			<div class="row banery"><span>Bannery</span>
				<div class="col-sm-6 element_inside h75"></div>
				<div class="col-sm-6 element_inside h75"></div>
				<div class="col-sm-6 element_inside h75"></div>
				<div class="col-sm-6 element_inside h75"></div>
			</div>
			<div class="row o_nas"><span>O nas</span>
				<div class="col-sm-12 element border h100"></div>
			</div>
			<div class="row polecane"><span>Polecane produkty</span>
				<div class="col-sm-12 element border h100"></div>
			</div>
			<div class="row stopka"><span>Stopka</span>
				<div class="col-sm-3 element_inside h100"></div>
				<div class="col-sm-3 element_inside h100"></div>
				<div class="col-sm-3 element_inside h100"></div>
				<div class="col-sm-3 element_inside h100"></div>
			</div>
		</div>

		<div class="modules_view col-md-6">

			<div class="title_group">Top Header</div>
				<div class="module">
					<span class="module-name">Logo sklepu</span>
					<a href="{$themes_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>
				<div class="module">
					<span class="module-name">Wyszukiwarka</span>
					<a href="{$search_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>
				<div class="module">
					<span class="module-name">Dane kontaktowe</span>
					<a href="{$contacts_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">Menu</div>
				<div class="module">
					<span class="module-name">Kategorie</span>
					<a href="{$categories_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>
				<div class="module">
					<span class="module-name">Menu</span>
					<a href="{$ps_mainmenu_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">Slider</div>
				<div class="module">
					<span class="module-name">Image slider</span>
					<a href="{$is_imageslider_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">Strefa marek</div>
				<div class="module">
					<span class="module-name">Ustawienia producentów</span>
					<a href="{$brands_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>
				<div class="module">
					<span class="module-name">Slider logo</span>
					<a href="{$ms_manufacturerslider_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>
			
			<div class="title_group">Bannery</div>
				<div class="module">
					<span class="module-name">Blok banerów na stornie głownej</span>
					<a href="{$ms_homebanners_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">O nas</div>
				<div class="module">
					<span class="module-name">Blok 'O nas' na stronie głównej</span>
					<a href="{$ms_homeaboutus_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">Polecane produkty</div>
				<div class="module">
					<span class="module-name">Polecane produkty</span>
					<a href="{$products_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

			<div class="title_group">Stopka</div>
				<div class="module">
					<span class="module-name">Odnośniki do mediów społecznościowych</span>
					<a href="{$ps_socialfollow_link}" class="btn mb-2 mt-1 btn-primary module-link">Konfiguruj</a>
				</div>

	


		</diV>
	</div>
</div>

{* <div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Documentation' mod='ms_themeconfigurator'}</h3>
	<p>
		&raquo; {l s='You can get a PDF documentation to configure this module' mod='ms_themeconfigurator'} :
		<ul>
			<li><a href="#" target="_blank">{l s='English' mod='ms_themeconfigurator'}</a></li>
			<li><a href="#" target="_blank">{l s='French' mod='ms_themeconfigurator'}</a></li>
		</ul>
	</p>
</div> *}
