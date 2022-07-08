{extends file="catalog/_partials/miniatures/product.tpl"}

{block name='product_miniature_item_class'}product-miniature--smaller{/block}

    {block name='product_miniature_item'}
        <div class="products-list__block">
            <div class="product-miniature">    
                {include file='catalog/_partials/miniatures/_partials/product-thumb.tpl' thumbExtraClass='mb-2'}
                <div class="product-miniature__product_info">
                    {include file='catalog/_partials/miniatures/_partials/product-title.tpl' titleExtraClass='mb-0'}

                    {include file='catalog/_partials/miniatures/_partials/product-prices.tpl' pricesExtraClass='mb-0'}
                </div>
            </div>
        </div>
    {/block}
{block name='product_form'}{/block}
{block name='quick_view'}{/block}
{block name='product_flags'}{/block}
{block name='product_reviews'}{/block}
