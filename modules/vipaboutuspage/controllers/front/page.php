<?php
/**
 * 2007-2013 PrestaShop
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
 *   @author    Buy-addons <contact@buy-addons.com>
 *   @copyright 2007-2015 PrestaShop SA
 *   @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *   International Registered Trademark & Property of PrestaShop SA
 */

class VipAboutUsPagePageModuleFrontController extends ModuleFrontController
{
   public function initContent()
    {
		$results = Db::getInstance()->executeS('SELECT `id_lang`, `content` FROM '._DB_PREFIX_.'vipaboutuspage_lang WHERE `id_vipaboutuspage`= 1 AND `id_lang`='.$this->context->language->id);

   	$this->display_column_left = false;
   	$this->display_column_right = false;


		$this->context->smarty->assign("content", $results[0]['content']);

			$this->setTemplate('vipaboutpage.tpl');

        parent::initContent();
    }
}
