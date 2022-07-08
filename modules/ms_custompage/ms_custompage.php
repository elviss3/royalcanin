<?php
/**
* 2007-2020 PrestaShop
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
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ms_custompage extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ms_custompage';
        $this->tab = 'administration';
        $this->author = 'Sebastian Koziołek - Millenium Studio';
        $this->version = '2.0.0';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Strefa marki RC');
        $this->description = $this->l('Dodtakowa strona \'Strefa Marki Royal Canin\'');

        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');

		$this->context->smarty->assign('module_dir', $this->_path);

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {

		$languages = Language::getLanguages(false);
		
		foreach ($languages as $lang) {
			Configuration::updateValue($this->name . '_meta', 'module-ms_custompage-page');
			Configuration::updateValue($this->name . '_meta_title_'.$lang['id_lang'], 'Custom page');
			Configuration::updateValue($this->name . '_meta_url_rewrite_'.$lang['id_lang'], 'custom-page');
		}

		$this->setMeta();


    	$sql = array();
    	require_once(dirname(__FILE__) . '/sql/install.php');
    	foreach ($sql as $sq):
    		if (!Db::getInstance()->Execute($sq))
    			return false;
    		endforeach;

    	Tools::clearSmartyCache();
    	Tools::clearXMLCache();
    	Media::clearCache();

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {

		$meta = Meta::getMetaByPage($this->name . '_meta', 1);

		if (isset($meta['id_meta'])) {
            $delMeta = new Meta($meta['id_meta']);
            $delMeta->delete();
        }

    	$sql = array();
    	require_once(dirname(__FILE__) . '/sql/uninstall.php');
    	foreach ($sql as $sq):
    		if (!Db::getInstance()->Execute($sq))
    			return false;
    		endforeach;

    	Tools::clearSmartyCache();
    	Tools::clearXMLCache();
    	Media::clearCache();

        return parent::uninstall();
    }

	public function setMeta()
	{
		$languages = Language::getLanguages(false);

		$metaName = Configuration::get($this->name . '_meta'); 
		$metaExist = Meta::getMetaByPage($metaName, $this->context->language->id);
		
			if ($metaExist) {
				$metaDel = Meta::getMetaByPage($metaName, $this->context->language->id);
				if (isset($metaDel['id_meta'])) {
					$delMeta = new Meta($metaDel['id_meta']);
					$delMeta->delete();
				}
			}

			$meta = new Meta();
			$meta->page = $metaName;
			$meta->configurable = 1;
			foreach (Language::getLanguages(false) as $language) {
				$meta->title[$language['id_lang']] = Configuration::get($this->name . '_meta_title_'.$this->context->language->id);
				$meta->url_rewrite[$language['id_lang']] = Configuration::get($this->name . '_meta_url_rewrite_'.$this->context->language->id);
			}
		return $meta->save();
	}

    /**
     * Load the configuration form
     */
    public function getContent()
    {
    	$this->_html = '';

        if (((bool)Tools::isSubmit('submitms_custompageModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return /*$output.*/$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {

    	$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->trans('Nazwa storny', array(), 'Modules.ms_custompage'),
						'name' => 'CUSTOM_PAGE_NAME',
						'lang' => true,
						'col' => 4
					),
					array(
						'type' => 'text',
						'label' => $this->trans('Przyjazny link', array(), 'Modules.ms_custompage'),
						'name' => 'CUSTOM_PAGE_URL',
						'lang' => true,
						'col' => 4
					),

                ),

				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);


    	$helper = new HelperForm();
    	$helper->show_toolbar = false;
    	$helper->table = $this->table;
    	$helper->module = $this;
    	$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    	$helper->default_form_language = $lang->id;
    	$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    	$helper->identifier = $this->identifier;
    	$helper->submit_action = 'submitms_custompageModule';
    	$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
    	$helper->token = Tools::getAdminTokenLite('AdminModules');
    	$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFormValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

    	return $helper->generateForm(array($fields_form));
    }

	protected function getConfigFormValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();
   
		foreach ($languages as $lang) {
			$fields['CUSTOM_PAGE_NAME'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_NAME', Configuration::get($this->name . '_meta_title_'. $lang['id_lang']));
			$fields['CUSTOM_PAGE_URL'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_URL', Configuration::get($this->name . '_meta_url_rewrite_'. $lang['id_lang']));
		}
        return $fields;
    }

    protected function postProcess()
    {
     	$languages = Language::getLanguages(false);
		$values = array();
		foreach ($languages as $lang) {
			// $values['CUSTOM_PAGE_NAME'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_NAME_'.$lang['id_lang']);
            // $values['CUSTOM_PAGE_URL'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_URL_'.$lang['id_lang']);

			Configuration::updateValue($this->name . '_meta_title_'.$lang['id_lang'], Tools::getValue('CUSTOM_PAGE_NAME_'.$lang['id_lang']));
			Configuration::updateValue($this->name . '_meta_url_rewrite_'.$lang['id_lang'], Tools::getValue('CUSTOM_PAGE_URL_'.$lang['id_lang']));

		}		

		// Configuration::updateValue($this->name . '_meta_title', $values['CUSTOM_PAGE_NAME']);
        // Configuration::updateValue($this->name . '_meta_url_rewrite', $values['CUSTOM_PAGE_URL']);

		$this->setMeta();

    	$this->_clearCache('custompage.tpl');

    	$this->_errors = array();
    }


    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
