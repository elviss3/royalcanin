{*
* BlockPriceRange Module
* 
*  @author    PremiumPresta <premiumpresta@gmail.com>
*  @copyright 2014 PremiumPresta
*  @license   http://creativecommons.org/licenses/by-nd/4.0/ CC BY-ND 4.0
*}
<div id="ms_blockpricerange" class="block text-center">
    {if $one_price}
        <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">{Tools::displayPrice($one_price|floatval)}</span>
    {else}
        <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">{Tools::displayPrice($product_price_min|floatval)} - {Tools::displayPrice($product_price_max|floatval)}</span>    
    {/if}
</div>