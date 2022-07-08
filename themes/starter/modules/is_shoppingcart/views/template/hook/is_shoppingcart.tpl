{**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="header-top__block header-top__block--cart order-3 order-lg-4">
  <div class="blockcart cart-preview dropdown" data-refresh-url="{$refresh_url}">
    <a href="#" role="button" id="cartDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="header-top__link d-lg-block d-none">
      <div class="header-top__icon-container">
        <span class="header-top__icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path data-name="Path 28" d="M0 0h40v40H0z" style="fill:none"/>
              <path data-name="Path 29" d="M27.186 21.8a3.582 3.582 0 0 0 3.149-1.854l6.443-11.682A1.793 1.793 0 0 0 35.212 5.6H8.577L6.885 2H1v3.6h3.6l6.479 13.662-2.43 4.392A3.606 3.606 0 0 0 11.8 29h21.6v-3.6H11.8l1.98-3.6zm-16.9-12.6h21.867l-4.967 9H14.552zM11.8 30.8a3.6 3.6 0 1 0 3.6 3.6 3.595 3.595 0 0 0-3.6-3.6zm18 0a3.6 3.6 0 1 0 3.6 3.6 3.595 3.595 0 0 0-3.6-3.6z"/>
          </svg>
        </span>
        <span class="header-top_descrpt">Koszyk</span>

        <span class="header-top__badge {if $cart.products_count > 9}header-top__badge--smaller{/if}">
          {$cart.products_count}
        </span>
      </div>
    </a>
    <a href="{$cart_url}" class="d-flex d-lg-none header-top__link">
      <div class="header-top__icon-container">
        <span class="header-top__icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
              <path data-name="Path 28" d="M0 0h40v40H0z" style="fill:none"/>
              <path data-name="Path 29" d="M27.186 21.8a3.582 3.582 0 0 0 3.149-1.854l6.443-11.682A1.793 1.793 0 0 0 35.212 5.6H8.577L6.885 2H1v3.6h3.6l6.479 13.662-2.43 4.392A3.606 3.606 0 0 0 11.8 29h21.6v-3.6H11.8l1.98-3.6zm-16.9-12.6h21.867l-4.967 9H14.552zM11.8 30.8a3.6 3.6 0 1 0 3.6 3.6 3.595 3.595 0 0 0-3.6-3.6zm18 0a3.6 3.6 0 1 0 3.6 3.6 3.595 3.595 0 0 0-3.6-3.6z"/>
          </svg>
        </span>
        <span class="header-top__badge {if $cart.products_count > 9}header-top__badge--smaller{/if}">
          {$cart.products_count}
        </span>
      </div>
    </a>
    <div class="dropdown-menu blockcart__dropdown cart-dropdown dropdown-menu-right" aria-labelledby="cartDropdown">
      <div class="cart-dropdown__content keep-open js-cart__card-body cart__card-body">
        <div class="cart-loader"><div class="spinner-border text-primary" role="status"><span class="sr-only">{l s='Loading...' d='Shop.Theme.Global'}</span></div></div>
        <div class="cart-dropdown__title d-flex align-items-center mb-3">
          <p class="h5 mb-0 mr-2">
            {l s='Your cart' d='Shop.Istheme'}
          </p>
          <span class="cart-dropdown__close dropdown-close ml-auto cursor-pointer">
            ×
          </span>
        </div>
        {if $cart.products_count > 0}
          <div class="cart-dropdown__products pt-3 mb-3">
            {foreach from=$cart.products item=product}
              {include 'module:is_shoppingcart/views/template/front/is_shoppingcart-product-line.tpl' product=$product}
            {/foreach}
          </div>

          {* {foreach from=$cart.subtotals item="subtotal"}
            {if $subtotal.value}
              <div class="cart-summary-line cart-summary-line-{$subtotal.type}">
                <span class="label">{$subtotal.label}</span>
                <span class="value">{if 'discount' == $subtotal.type}-&nbsp;{/if}{$subtotal.value}</span>
              </div>
            {/if}
          {/foreach} *}

          <div class="cart-summary-line cart-total">
            <span class="label">{$cart.totals.total.label}</span>
            <span class="value">{$cart.totals.total.value}</span>
          </div>

          <div class="mt-3">
            <a href="{$cart_url}" class="btn btn-sm btn-primary btn-block dropdown-close">
              {l s='Proceed to checkout' d='Shop.Theme.Actions'}
            </a>
          </div>

        {else}
          <div class="alert alert-warning">
            {l s='Unfortunately your basket is empty' d='Shop.Istheme'}
          </div>
        {/if}
      </div>
    </div>
  </div>
</div>
