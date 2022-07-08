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
 *)
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<div class="header-top__block header-top__block--user order-2 order-lg-3">
  <a
    class="header-top__link"
    rel="nofollow"
    href="{$urls.pages.my_account}"
    {if $logged}
      title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
    {else}
      title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
    {/if}
  >
    <div class="header-top__icon-container">
          <span class="header-top__icon">
            <svg
            width="40"
            height="40"
            viewBox="0 0 40 40"
            version="1.1"
            id="svg6"
            sodipodi:docname="172bbf2f95ea9a21e8b162645c51233c.svg"
            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg">
          <defs
              id="defs10" />
          <sodipodi:namedview
              id="namedview8"
              pagecolor="#ffffff"
              bordercolor="#666666"
              borderopacity="1.0"
              inkscape:pageshadow="2"
              inkscape:pageopacity="0.0"
              inkscape:pagecheckerboard="0" />
          <path
              data-name="Path 26"
              d="M 0,0 H 40 V 40 H 0 Z"
              style="fill:none"
              id="path2" />
          <path
              data-name="Path 27"
              d="M 20,2 A 18,18 0 1 0 38,20 18.007,18.007 0 0 0 20,2 Z m -8.874,29.3 c 0.774,-1.62 5.49,-3.2 8.874,-3.2 3.384,0 8.118,1.584 8.874,3.2 a 14.266,14.266 0 0 1 -17.748,0 z M 31.448,28.69 C 28.874,25.562 22.628,24.5 20,24.5 c -2.628,0 -8.874,1.062 -11.448,4.194 a 14.4,14.4 0 1 1 22.9,0 z M 20,9.2 A 6.3,6.3 0 1 0 26.3,15.5 6.284,6.284 0 0 0 20,9.2 Z m 0,9 a 2.7,2.7 0 1 1 2.7,-2.7 2.7,2.7 0 0 1 -2.7,2.7 z"
              id="path4" />
          </svg>
        </span>
        <span class="header-top_descrpt d-none d-lg-block">Twoje konto</span>
    </div>
  </a>
</div>
