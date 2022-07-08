{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{block name='header_banner'}
  <div class="header-banner">
    {hook h='displayBanner'}
  </div>
{/block}

{block name='header_nav'}
  <nav class="header-nav mb-0 py-1 d-block d-lg-block ">
    <div class="container">
      <div class="row align-items-center">
        {hook h='displayNav1'}
        {hook h='displayNav2'}
      </div>
    </div>
  </nav>
{/block}

{block name='header_top'}
  <div class="header-top">
    <div class="container">

       <div class="row header-top__row flex-wrap">

        <div class="col-lg-4 col header-top__block header-top__block--logo order-1 order-lg-1">
          {if $page.page_name == 'index'}
            <h1 class="mb-0">
          {/if}
            <a href="{$urls.pages.index}">
              <img
                {if !empty($shop.logo_details)}
                  src="{$shop.logo_details.src}"
                  width="{$shop.logo_details.width}"
                  height="{$shop.logo_details.height}"
                {else}
                  src="{$shop.logo}"
                {/if}
                class="logo img-fluid"
                alt="{$shop.name} {l s='logo' d='Shop.Theme.Global'}">
            </a>
          {if $page.page_name == 'index'}
            </h1>
          {/if}
        </div>

        <div class="break d-lg-none order-5 order-lg-7"></div>

        <div class="flex-grow-0 header-top__block header-top__block--menu-toggle d-block d-lg-none order-6 order-lg-7">
          <a
            class="header-top__link"
            rel="nofollow"
            href="#"
            data-toggle="modal"
            data-target="#mobile_top_menu_wrapper"
            >

            {* <div class="header-top__icon-container">
              <span class="header-top__icon material-icons">menu</span>
            </div> *}
            <div class="header-top__icon-container hamburger-icon" id="icon">
              <div class="icon-1" id="a"></div>
              <div class="icon-2" id="b"></div>
              <div class="icon-3" id="c"></div>
              <div class="clear"></div>
              <span class="position-relative">{l s="Menu" d='Shop.Theme.Global'}</span>
            </div>
            



          </a>
        </div>

        {hook h='displayTop'}

        <div class="break d-lg-none order-10 order-lg-10"></div>

      </div>
    </div>
  </div>
  {hook h='displayNavFullWidth'}
{/block}

        
