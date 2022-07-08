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

<div id="js-product-list-top" class="row products-selection align-items-center mb-4">

<div class="msManufacturer col-xs-12">
    {hook h='msManufacturer' mod='ms_manufacturerslider'}
</div>

  {* <div class="col-auto hidden-lg-up">
    {if !empty($listing.rendered_facets)}
      <button data-target="#mobile_filters" data-toggle="modal" class="display-toggle__link d-inline-block">
        <i class="mdi mdi-filter"></i>
      </button>
    {/if}
  </div> *}

  <div class="col-auto d-block d-sm-block mr-auto">
    <ul class="display-toggle d-flex align-items-center mx-n1 m-0">
      <li class="display-toggle__elem px-1 hidden-lg-up">
        {if !empty($listing.rendered_facets)}
          <button data-target="#mobile_filters" data-toggle="modal" class="display-toggle__link d-inline-block {if $activeFilters|count}active{/if}">
            <i class="mdi mdi-filter"></i>
          </button>
        {/if}
      </li>
      <li class="display-toggle__elem px-1">
        <a href="#" data-toggle-listing data-display-type="grid" class="display-toggle__link d-inline-block {if $listingDisplayType == 'grid'}active{/if}">
          <i class="mdi mdi-view-grid-outline"></i>
        </a>
      </li>
      <li class="display-toggle__elem px-1">
        <a href="#" data-toggle-listing data-display-type="list" class="display-toggle__link d-inline-block {if $listingDisplayType == 'list'}active{/if}">
          <i class="mdi mdi-view-list"></i>
        </a>
      </li>
    </ul>
  </div>

  <div class="col ml-auto select_form-group text-right">
    {block name='sort_by'}
      {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
    {/block}
  </div>

  {* <div class="col-auto">
    {block name='sort_by'}
      {include file='catalog/_partials/per-page.tpl'}
    {/block}
  </div> *}

  

  

  {block name='pagination'}
    {include file='_partials/pagination-top.tpl' pagination=$listing.pagination}
  {/block}

</div>
